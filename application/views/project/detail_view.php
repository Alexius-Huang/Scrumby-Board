<div class="row">
  <div class="col-md-offset-1 col-md-10">
    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('book'); ?> <?php echo $project['title']; ?> </h3>
        </div>
        <div class="card-content">
          
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('pie-chart'); ?> Scrumby Gantt Chart</h3>
        </div>
        <div class="card-content">
          <div id="gantt-chart"></div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('archive'); ?> <?php echo $project['title']; ?></h3>
        </div>
        <!-- Should Modify to become React.js Component -->
        <div class="card-content horizontal">
          <div id="template-card"></div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('calendar'); ?> Scrumby Calendar</h3>
        </div>
        <div class="content"><p class="hint text-center"><?php echo add_icon('wrench'); ?> Under Construction</p></div>
      </div>
    </div>

  </div>
</div>

<script type="text/babel">
  var TemplateCard = React.createClass({
    getInitialState: function() {
      return {'templateCardCount': {}};
    },
    pluralize: function(number, single, plural) {
      if (parseInt(number) == 1) {
        return "1 " + single.toString();
      } else if (parseInt(number) == 0) {
        return "0 " + plural.toString();
      } else {
        return number.toString() + " " + plural.toString();
      }
    },
    handleCardDelete: function(cardId, templateId, ganttTaskId) {
      $.ajax({
        type: 'post',
        url: this.props.currentURL,
        data: {card_id: cardId, delete: 'true'},
        success: function() {
          var cardSelector = "div#card-" + cardId;
          var cardCountSelector = "div#template-" + templateId + "-card-count";
          $(cardSelector).fadeOut(500, 'swing');
          var cardCount = parseInt($(cardCountSelector).html().split(' ').filter(function(item) { return (!isNaN(item));}));
          if (cardCount == 1) {
            $(cardCountSelector).html("Currently There Are No Tasks");
          } else {
            $(cardCountSelector).html("Currently " + this.pluralize(cardCount - 1, 'Task', 'Tasks'));
          }
          if (ganttTaskId != null) { 
            $('input[name="gantt_task_id"]').val(ganttTaskId);
          }
          swal({
            title: "Deleted!",
            text: "You have deleted this card.",
            type: "success",
            showConfirmButton: false,
            timer: 1000
          });
        }.bind(this),
        error: function(xhr, status, err) {
          console.error(this.props.url, status, err.toString());
        }.bind(this)
      });
    },
    render: function() {
      return (
      <ul className="list-inline" id="template-section">
        <?php foreach ($templates as $template): ?>
          <li className="inner-card">
            <div className="card-block">
              <div className="card-title">
                <h4><?php echo react_add_icon('tasks'); ?> <?php echo $template['name']; ?></h4>
              </div>
              <?php if ( ! empty($template_card_table[$template['id']])): ?>
                <?php foreach ($template_card_table[$template['id']] as $card): ?>
                  <?php
                    switch((int)$card['type_id']) {
                      case 1:
                        $card_type = "text";
                        break;
                      case 2:
                        /* Render Image Content */
                        $card_type = "image";
                        $image = $image_model->get_image_file_by_references('Card', $card['id']);
                        break;
                      case 3:
                        /* Render YouTube Content */
                        $card_type = "youtube";
                        $youtube = $youtube_model->get_youtube_link_by_references('Card', $card['id']);
                        $youtube_image_link_mq = get_youtube_image_link($youtube['key'], 3);
                        // $youtube_info = get_youtube_info($youtube['key']);
                        break;
                    }
                  ?>
                  <CardComponent 
                    cardId="<?php echo $card['id']; ?>"
                    cardType="<?php echo $card_type; ?>"
                    templateId = "<?php echo $template['id']; ?>"
                    cardIdSelector="card-<?php echo $card['id']; ?>"
                    title="<?php echo $card['title']; ?>"
                    viewURL="<?php echo base_url(); ?>card/detail/<?php echo $card['id']; ?>"
                    settingURL="<?php echo base_url(); ?>card/setting/<?php echo $card['id']; ?>"
                    
                    <?php if ($ganttTask = $this->gantt_chart_model->get_gantt_chart_task_by_card_id($card['id'])): ?>
                      ganttTaskId="<?php echo $ganttTask['id']; ?>"
                    <?php endif; ?>

                    <?php switch ($card['type_id']):
                            case 2: ?>
                              imageId="<?php echo explode('.', $image['file_name'])[0]; ?>"
                              imageFile="<?php echo $image['file_name']; ?>"
                              imageFileURL="<?php echo get_image_link('Card', $image['file_name']); ?>"
                        <?php break;
                            case 3: ?>
                              youtubeKey="<?php echo $youtube['key']; ?>"
                              youtubeImageLink="<?php echo $youtube_image_link_mq; ?>"
                        <?php break;
                          endswitch; ?>
                    
                    onCardDelete={this.handleCardDelete}
                  ><?php echo $card['content']; ?></CardComponent>
                <?php endforeach; ?>
                <div className="card-content template-card-style text-center" id="template-<?php echo $template['id']; ?>-card-count">
                  Currently <?php echo pluralize(count($template_card_table[$template['id']]), 'Task', 'Tasks'); ?>
                </div>
              <?php else: ?>
                <div className="card-content template-card-style text-center" id="template-<?php echo $template['id']; ?>-card-count">
                  Currently There Are No Tasks
                </div>
              <?php endif; ?>
              <CardBtnComponent
                addCardURL="<?php echo base_url(); ?>card/new_card/<?php echo $template['id']; ?>"
              />
            </div>
          </li>
        <?php endforeach; ?>
        <AppendTemplateItem url={this.props.currentURL} />
      </ul>
      );
    }
  });

  var CardComponent = React.createClass({
    handleDeleteCardClick: function(event) {
      event.preventDefault();
      swal({
        title: "Delete Card",
        text: "Are you sure to delete this card?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, don't do it!",
        closeOnConfirm: false,
        closeOnCancel: true
      },
        function(confirmed) {
          if (confirmed) {
            var ganttTaskId;
            if (this.props.ganttTaskId) {
              ganttTaskId = this.props.ganttTaskId;
            } else {
              ganttTaskId = null;
            }
            this.props.onCardDelete(
              this.props.cardId,
              this.props.templateId,
              ganttTaskId
            );
          }
        }.bind(this)
      );
    },
    render: function() {
      var embedContentNode, iconName;
      switch(this.props.cardType) {
        case 'text':
          embedContentNode = undefined;
          iconName = "info";
          break;
        case 'image':
          embedContentNode = (<CardImage
            title={this.props.title}
            imageId={this.props.imageId}
            imageFile={this.props.imageFile}
            imageFileURL={this.props.imageFileURL}
          >{this.props.children}</CardImage>);
          iconName = "image";
          break;
        case 'youtube':
          embedContentNode = (<CardYoutube
            title={this.props.title}
            youtubeKey={this.props.youtubeKey}
            youtubeImageLink={this.props.youtubeImageLink}
          >{this.props.children}</CardYoutube>);
          iconName = "youtube-play";
          break;
      }
      return (
        <div class="cardComponent" id={this.props.cardIdSelector}>
          <div className="card-content template-card-style">
            <h4><i className={'fa fa-' + iconName} aria-hidden="true"></i> {this.props.title}</h4>
            {embedContentNode}
            <p>{this.props.children}</p>
          </div>
          <div className="row control-actions">
            <div className="col-md-4 btn-left">
              <a
                href={this.props.viewURL}
                data-toggle="tooltip"
                data-placement="bottom"
                title="View Card"
              ><?php echo react_add_icon('eye'); ?></a>
            </div>
            <div className="col-md-4 btn-center">
              <a
                href={this.props.settingURL}
                data-toggle="tooltip"
                data-placement="bottom"
                title="Card Setting"
              ><?php echo react_add_icon('wrench'); ?></a>
            </div>
            <div className="col-md-4 btn-right">
              <a 
                href="#"
                data-toggle="tooltip"
                className="btn-right"
                data-placement="bottom"
                title="Delete Card"
                onClick={this.handleDeleteCardClick}
              ><?php echo react_add_icon('remove'); ?></a>
            </div>
          </div>
        </div>
      );
    }
  });

  var CardImage = React.createClass({
    handleShowImageContent: function(event) {
      event.preventDefault();
      if ((typeof this.props.children) != 'string') {
        var content = this.props.children.reduce(function(previous, current) {
          if ((typeof current) == 'object') {
            if (current.type == 'br') {
              return previous + '<br />';
            }
          } else {
            return previous + current;
          }
        }, '');
      } else { var content = this.props.children; }
      var htmlText = '<div class="row"><div id="image-popout" class="col-md-offset-1 col-md-10"><img src="' + this.props.imageFileURL + '" alt="Show Image"></div></div>' + 
      '<br /><div class="row"><div class="col-md-offset-1 col-md-10"><p class="image-popout-text">' + content + '</p></div></div>';
      swal({
        title: this.props.title,
        text: htmlText,
        html: true,
        showConfirmButton: true,
        showCancelButton: false,
        confirmButtonText: 'Close',
        allowOutsideClick: true,
        animation: 'slide-from-top',
        customClass: 'show-image'
      });
    },
    render: function() {
      return (
        <div className="cardImage">
          <a
            href="#"
            id={this.props.imageId}
            onClick={this.handleShowImageContent}
          ><img src={this.props.imageFileURL} alt="Show Image" /></a>
        </div>
      );
    }
  });

  var CardYoutube = React.createClass({
    handleOpenYoutubeVideo: function(event) {
      event.preventDefault();
      if ((typeof this.props.children) != 'string') {
        var content = this.props.children.reduce(function(previous, current) {
          if ((typeof current) == 'object') {
            if (current.type == 'br') {
              return previous + '<br />';
            }
          } else {
            return previous + current;
          }
        }, '');
      } else { var content = this.props.children; }
      var youtubeFrame = function(width, height) {
        return '<iframe width="' + width + '" height="' + height + '" src="https://www.youtube.com/embed/' + this.props.youtubeKey + '" frameborder="0" allowfullscreen></iframe>';
      }.bind(this); 
      var htmlText = '<div class="row"><div id="youtube-frame" class="col-md-offset-1 col-md-10"></div></div>' +
      '<br /><div class="row"><div class="col-md-offset-1 col-md-10"><p class="youtube-popout-text">' + content + '</p></div></div>';
      swal({
        title: this.props.title,
        text: htmlText,
        html: true,
        showConfirmButton: true,
        showCancelButton: false,
        confirmButtonText: 'Close',
        allowOutsideClick: true,
        animation: 'slide-from-top',
        customClass: 'play-youtube'
      });
      var width = $('div#youtube-frame').width();
      var height = parseInt(width / 16 * 9);
      $('div#youtube-frame').append(youtubeFrame(width, height));
    },
    render: function() {
      return (
        <div className="cardYoutube">
          <a
            href="#"
            id={"youtube-" + this.props.youtubeKey}
            onClick={this.handleOpenYoutubeVideo}
          ><img src={this.props.youtubeImageLink} alt="Youtube Image" /></a>
        </div>
      );
    }
  });

  var CardBtnComponent = React.createClass({
    render: function() {
      return (
        <div className="card-content">
          <div className="row control-actions">
            <div className="col-md-12">
              <a
                href={this.props.addCardURL}
                className="<?php // echo ($status > 1) ? '' : 'disabled'; ?>"
              ><?php echo react_add_icon('plus'); ?> New Card</a>
            </div>
          </div>
        </div>
      );
    }
  });

  var AppendTemplateItem = React.createClass({
    handleAddTemplate: function(event) {
      event.preventDefault();
      swal({
        title: 'Add Template',
        text: 'Input new template name.',
        type: 'input',
        showCancelButton: true,
        closeOnConfirm: false,
        animation: 'slide-from-top',
        inputPlaceholder: 'Template name ...'
      },
        function(inputValue) {
          if (inputValue === false) {
            return false;
          } else if (inputValue === '') {
            swal({
              title: 'Oops!',
              text: 'You need to gave a name to your new template!',
              type: 'error',
              showConfirmButton: false,
              timer: 1000
            });
          }
          $.post(
            this.props.url,
            {'new_template': 'true', 'template_name': inputValue},
            function(data) {
              swal({
                title: 'Template Added!',
                text: 'Template ' + inputValue + ' has been successfully added!',
                type: 'success',
                showConfirmButton: false,
                timer: 1000
              });
              setTimeout(function() {
                location.reload();
              }, 1100);
            }
          );
        }.bind(this)
      );
    },
    render: function() {
      return (
        <li className="inner-card" id="add-template-card">
          <div className="card-block">
            <div className="card-title">
              <h4><?php echo react_add_icon('plus-square'); ?> Add Template</h4>
            </div>
            <div className="card-content template-card-style text-center add-template-title">
              Currently <?php echo pluralize(count($templates), 'Template', 'Templates'); ?>
            </div>
            <div className="card-content add-template-content">
              <div className="row control-actions">
                <div className="col-md-12">
                  <a 
                    href="#"
                    onClick={this.handleAddTemplate}
                  ><?php echo react_add_icon('plus'); ?>Add template</a>
                </div>
              </div>
            </div>
          </div>
        </li>
      );
    }
  });

  ReactDOM.render(
    <TemplateCard
      currentURL="<?php echo base_url(); ?>project/detail/<?php echo $project['id']; ?>"
    />,
    document.getElementById('template-card')
  );
</script>

<?php if ( ! empty($gantt_chart_data)): ?>
  <script type="text/babel">
    google.charts.load('current', {'packages':['gantt']});
    var chart;

    var GanttChart = React.createClass({
      getInitialState: function() {
        var ganttChartData = [];
        <?php foreach ($gantt_chart_data as $data): ?>
          <?php $start_arr = explode('-', $data['start_date']); ?>
          <?php $end_arr   = explode('-', $data['end_date']);   ?>
          ganttChartData.push({
            taskId:       "<?php echo $data['task_id']; ?>",
            taskName:     "<?php echo $data['task_name']; ?>",
            resource:     "<?php echo $data['resource']; ?>",
            startDate:    new Date("<?php echo $start_arr[0]; ?>", "<?php echo $start_arr[1]; ?>" - 1, "<?php echo $start_arr[2]; ?>"),
            endDate:      new Date("<?php echo $end_arr[0]; ?>", "<?php echo $end_arr[1]; ?>" - 1, "<?php echo $end_arr[2]; ?>"),
            duration:     null,
            percentage:   <?php echo $data['percentage']; ?>,
            dependencies: null
          });
        <?php endforeach ?>
        return { ganttChartData: ganttChartData };
      },
      setupGanttChartParams: function(chartData) {
        chartData.addColumn('string', 'Task ID');
        chartData.addColumn('string', 'Task Name');
        chartData.addColumn('string', 'Resource');
        chartData.addColumn('date', 'Start Date');
        chartData.addColumn('date', 'End Date');
        chartData.addColumn('number', 'Duration');
        chartData.addColumn('number', 'Percent Complete');
        chartData.addColumn('string', 'Dependencies');
      },
      drawGanttChart: function() {
        var data = new google.visualization.DataTable();
        this.setupGanttChartParams(data);

        var rows = [];
        this.state.ganttChartData.map(function(data) {
          rows.push([data.taskId, data.taskName, data.resource, data.startDate, data.endDate, data.duration, data.percentage, data.dependencies]);
        }.bind(this));

        data.addRows(rows);

        var height;
        if (rows.length > 5) {
          height = 200 + (rows.length - 5) * 30;
        } else {
          height = 200;
        }

        var options = {
          height: height,
          gantt: {
            trackHeight: 30,
            labelStyle: {
              fontName: 'Rubik'
            }
          }
        };

        chart = new google.visualization.Gantt(document.getElementById(this.props.renderChartId));

        google.visualization.events.addListener(chart, 'error', function (googleError) {
          google.visualization.errors.removeError(googleError.id);
          $('div#gantt-chart').html('<p class="hint text-center"><?php echo add_icon('smile-o'); ?> Currently there are no gantt chart task!</p>');
        });

        chart.draw(data, options);
      },
      componentDidMount: function() {
        if (this.state.ganttChartData == []) {
        }
        google.charts.setOnLoadCallback(this.drawGanttChart);
        setInterval(function() {
          var selector = 'input[name="gantt_task_id"]';
          if (this.state.ganttChartData != []) {
            if (($(selector).val() != "")) {
              var ganttChartData = this.state.ganttChartData.filter(function(data) {
                return data.taskId != $(selector).val();
              }.bind(this));
              $(this.props.renderChartId).html("");

              this.setState({ ganttChartData: ganttChartData });
              $(selector).val("");
              var data = new google.visualization.DataTable();
              this.setupGanttChartParams(data);

              var rows = [];
              ganttChartData.map(function(data) {
                rows.push([data.taskId, data.taskName, data.resource, data.startDate, data.endDate, data.duration, data.percentage, data.dependencies]);
              }.bind(this));

              data.addRows(rows);
              
              var height;
              if (rows.length > 5) {
                height = 200 + (rows.length - 5) * 30;
              } else {
                height = 200;
              }

              var options = {
                height: height,
                gantt: {
                  trackHeight: 30,
                  labelStyle: {
                    fontName: 'Rubik'
                  }
                }
              };

              chart = new google.visualization.Gantt(document.getElementById(this.props.renderChartId));

              google.visualization.events.addListener(chart, 'error', function (googleError) {
                google.visualization.errors.removeError(googleError.id);
                $('div#gantt-chart').slideUp(500, function() {
                  $(this).html('<p class="hint text-center"><?php echo add_icon('smile-o'); ?> Currently there are no gantt chart task !</p>');
                  $(this).slideDown(500, 'swing');
                })
              });

              chart.draw(data, options);
            }
          }
        }.bind(this), 2000);
      },
      render: function() {
        return (
          <div>
            <div id={this.props.renderChartId}></div>
            <input
              type="hidden"
              name="gantt_task_id"
              value=""
            />
          </div>
        );
      }
    });

    ReactDOM.render(
      <GanttChart renderChartId="draw-chart" />,
      document.getElementById('gantt-chart')
    );

  </script>
<?php else: ?>
  <script>
    $('div#gantt-chart').html('<p class="hint text-center"><?php echo add_icon('smile-o'); ?> Currently there are no gantt chart task !</p>');
  </script>
<?php endif; ?>