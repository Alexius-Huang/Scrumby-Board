<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="card" id="setting_card">
      <img src="<?php echo base_url(); ?>assets/images/CardSettingBGI.jpg" alt="Card Setting BGI" class="card-image" />
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('wrench'); ?> Card Setting</h3>
        </div>
        <div class="card-content">
          <div id="card-setting-form"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/babel">
  var CardSettingForm = React.createClass({
    componentDidMount: function() {
      $('.default-check').attr('checked', true);
    },
    handleSaveChangeClick: function(event) {
      event.preventDefault();
      if (($('input[name="origin_type"]').val() != '2') && $('input#radio-2').is(':checked')) {
        if ($('input[name="upload_image"]').val() == '') {
          swal_pop('error', 'You should upload a file!');
        } else {
          $('form.cardSettingForm').submit();
        }
      } else {
        $('form.cardSettingForm').submit();
      }
    },
    render: function() {
      return (
        <form method="post" action={this.props.url} className="cardSettingForm" encType="multipart/form-data">
          
          <div className="gap-20"></div>
          <?php if ( ! empty($warning_message)) { echo '<WarningMessage />'; } ?>
          <div className="gap-20"></div>

          <hr />
          
          <CardBasicInfo
            maxTitleLength={this.props.maxTitleLength}
            maxContentLength={this.props.maxContentLength}
          />
          
          <hr />

          <CardExtensions />

          <hr />

          <div className="form-group">
            <input type="hidden" name="submitted" value="1" />
          </div>
          <button
            className="btn btn-submit pull-right"
            onClick={this.handleSaveChangeClick}
          >Save Change</button>
          <div className="gap-50"></div>

        </form>
      );
    }
  });

  var CardBasicInfo = React.createClass({
    getInitialState: function() {
      return {
        title: '<?php echo $card['title'] ?>',
        content: "<?php echo br_to_newline($card['content']); ?>",
        titleWordCount:  this.props.maxTitleLength - "<?php echo $card['title']; ?>".length,
        contentWordCount: this.props.maxContentLength - "<?php echo $card['content']; ?>".length
      };
    },
    handleTitleChange: function(event) {
      var wordCount = this.props.maxTitleLength - event.target.value.length;
      if (wordCount < 0) {
        swal({
          title: 'Oops!',
          text: 'Limited card title length is ' + this.props.maxTitleLength + '!',
          type: 'error',
          showConfirmButton: false,
          timer: 1000
        });
      } else {
        this.setState({
          title: event.target.value,
          titleWordCount: wordCount
        });
      }
    },
    handleContentChange: function(event) {
      var wordCount = this.props.maxContentLength - event.target.value.length;
      if (wordCount < 0) {
        swal({
          title: 'Oops!',
          text: 'Limited card content length is ' + this.props.maxContentLength + '!',
          type: 'error',
          showConfirmButton: false,
          timer: 1000
        });
      } else {
        this.setState({
          content: event.target.value.replace('\\n', '\n'),
          contentWordCount: wordCount
        });
      }
    },
    componentDidMount: function() {
      <?php if ($card['type_id'] == 2): ?>
        ReactDOM.render(
          <EmbedImageContent
            imageId="<?php echo explode('.', $image['file_name'])[0]; ?>"
            imageFileURL="<?php echo $image['file_path']; ?>"
          />,
          document.getElementById('embed-additional-content')
        );
      <?php elseif ($card['type_id'] == 3): ?>
        ReactDOM.render(
          <EmbedYouTubeContent
            link="<?php echo $youtube['base_url']; ?>"
            key="<?php echo $youtube['key']; ?>"
            imageLink="<?php echo $youtube['image_link']; ?>"
          />,
          document.getElementById('embed-additional-content')
        );
      <?php endif; ?>
    },
    render: function() {
      return (
        <div class="cardBasicInfo">
          <h4 className="form-section-title"><?php echo react_add_icon('pencil'); ?> Card Basic Info</h4>

          <FixedLabel label="Card Opened By"><?php echo $this->user_model->get_user($card['user_id'])['username']; ?></FixedLabel>
          <FixedLabel label="Project Section"><?php echo $template['name']; ?></FixedLabel >

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Card Title</h4>
            </div>
            <div className="col-md-8 text-left">
              <input 
                type="text"
                name="title"
                className="form-control"
                placeholder="Enter your card name ..."
                onChange={this.handleTitleChange}
                value={this.state.title}
              />
              <p className="hint">{this.state.titleWordCount} Words Limited</p>
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Card Content</h4>
            </div>
            <div className="col-md-8 text-left">
              <textarea
                name="content"
                rows="5"
                className="form-control"
                placeholder="Enter your card name ..."
                onChange={this.handleContentChange}
                value={this.state.content}
              ></textarea>
              <p className="hint">{this.state.contentWordCount} Words Limited</p>
            </div>
          </div>
          
          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Card Type</h4>
              <input type="hidden" name="origin_type" value="<?php echo $card['type_id']; ?>" />
            </div>
            <div className="col-md-8 text-left">
              <RadioButtonList />
            </div>
          </div>

          <div id="embed-additional-content"></div>

        </div>
      );
    }
  });

  var RadioButtonList = React.createClass({
    render: function() {
      return (
        <ul id="radio-btn-list">
          <li><CardTypeRadioButton id="radio-1" value="1" class="radio-btn <?php if ($card['type_id'] == '1') { echo "default-check";} ?>">Plain Text</CardTypeRadioButton></li>
          <li><CardTypeRadioButton id="radio-2" value="2" class="radio-btn <?php if ($card['type_id'] == '2') { echo "default-check";} ?>">Embed Image</CardTypeRadioButton></li>
          <li><CardTypeRadioButton id="radio-3" value="3" class="radio-btn <?php if ($card['type_id'] == '3') { echo "default-check";} ?>">Embed YouTube Video</CardTypeRadioButton></li>
        </ul>
      );
    }
  });

  var CardTypeRadioButton = React.createClass({
    componentDidMount: function() {
      var selector_1 = "input#" + this.props.id;
      var selector_2 = "div#embed-additional-content";
      $(selector_2).css('display', 'hidden');
      $(selector_1).click(function() {
        if ($(selector_1).is(":checked")) {
          switch(parseInt($(selector_1).val())) {
            case 1:
              if ($(selector_2).html() != "") {
                $(selector_2).slideUp(500, "swing", function() {
                  $(selector_2).html("");
                });
              }
              break;
            case 2:
              $(selector_2).slideUp(500, "swing", function() {
                $(selector_2).html("");
                ReactDOM.render(
                  <EmbedImageContent
                    <?php if ($card['type_id'] == '2'): ?>
                      imageId="<?php echo explode('.', $image['file_name'])[0]; ?>"
                      imageFileURL="<?php echo $image['file_path']; ?>"
                    <?php endif; ?>
                  />,
                  document.getElementById('embed-additional-content')
                );
                $(selector_2).slideDown(500, "swing");
              });
              break;
            case 3:
              $(selector_2).slideUp(500, "swing", function() {
                $(selector_2).html("");
                ReactDOM.render(
                  <EmbedYouTubeContent
                    <?php if ($card['type_id'] == '3'): ?>
                      link="<?php echo $youtube['base_url']; ?>"
                      key="<?php echo $youtube['key']; ?>"
                      imageLink="<?php echo $youtube['image_link']; ?>"
                    <?php endif; ?>
                  />,
                  document.getElementById('embed-additional-content')
                );
                $(selector_2).slideDown(500, "swing");
              });
              break;
          }
        }
      });
    },
    render: function() {
      var btnTextId = "radio-text-" + this.props.value;
      return (
        <div class="cardTypeRadioButton">
          <input 
            type="radio"
            name="card_type"
            value={this.props.value}
            className={this.props.class}
            id={this.props.id}
          /> <span id={btnTextId} className="radio-text">{this.props.children}</span>
        </div>
      );
    }
  });

  var EmbedImageContent = React.createClass({
    getInitialState: function() {
      return {
        imageId: this.props.imageId,
        imagePreviewURL: this.props.imageFileURL,
        file: ''
      };
    },
    handleFileChange: function(event) {
      var reader = new FileReader();
      var file = event.target.files[0];
      var fileExt = file.name.match(/\.([0-9a-z]+)(?:[\?#]|$)/i)[1];
      if ((fileExt != 'jpg') && (fileExt != 'png') && (fileExt != 'gif')) {
        swal_pop('error', 'You should upload only these types of image : JPG, PNG or GIF!');
        $('input[name="upload_image"]').val("");
      } else if (parseInt(file.size) > (2 * 1024 * 1024)) {
        swal_pop('error', 'Your file exceeds the size of 2MB! Please try again!');
        $('input[name="upload_image"]').val("");
      } else {
        $('div#image-preview').fadeOut(500, 'swing', function() {
          reader.onloadend = function() {
            this.setState({
              file: file,
              imagePreviewURL: reader.result
            });
          }.bind(this);
          reader.readAsDataURL(file);
          $('div#image-preview').fadeIn(500, 'swing');
        }.bind(this));
      }
    }, 
    render: function() {
      var imagePreviewURL = this.state.imagePreviewURL;
      if (imagePreviewURL) {
        var imagePreviewNode = (<img src={imagePreviewURL} alt="Image Preview" />);
      } else {
        var imagePreviewNode = (<p>Please Select An Image To Preview</p>);
      }
      return (
        <div class="embedImageContent">
          <hr />
          <h4 className="form-section-title"><i className="fa fa-pencil"></i> Embed Content</h4>
          
          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Embed Image</h4>
            </div>
            <div className="col-md-8 text-left">
              <input
                type="file"
                name="upload_image"
                className="form-control"
                onChange={this.handleFileChange}
              />
            </div>
          </div>
          
          <FixedLabel label="Image Preview">
            <div id="image-preview">
              {imagePreviewNode}
            </div>
          </FixedLabel>  
        </div>
      );
    }
  });

  var EmbedYouTubeContent = React.createClass({
    getInitialState: function() {
      return {
        link: this.props.link,
        key: this.props.key,
        previewImageLink: this.props.imageLink
      };
    },
    handleLinkChange: function(event) {
      var link = event.target.value;
      var youtube_base_url = "https://www.youtube.com/watch?v=";
      var key = link.substr(youtube_base_url.length, link.length);
      key = key.split("&")[0];
      this.setState({ link: link, key: key });
      if (link.match(/^https:\/\/www.youtube.com\/watch\?v\=/)){
        $('div#youtube-preview').fadeOut(500, 'swing', function() {
          setTimeout(function() {
            this.setState({ previewImageLink: get_youtube_image_link(key, 4) });
            $('div#youtube-preview').fadeIn(500, 'swing');
          }.bind(this), 50);
        }.bind(this));
      } else {
        this.setState({ previewImageLink: '' });
      }
    },
    render: function() {
      var imagePreviewURL = this.state.previewImageLink;
      if (imagePreviewURL) {
        var imagePreviewNode = (<img src={imagePreviewURL} alt="Preview YouTube Image" />);
      } else {
        var imagePreviewNode = (<p>Input YouTube Link To Show Thumbnail of the Video</p>);
      }
      return (
        <div className="embedYouTubeContent">
          <hr />
          <h4 className="form-section-title"><i className="fa fa-pencil"></i> Embed Content</h4>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Embed YouTube</h4>
            </div>
            <div className="col-md-6 text-left">
              <input
                type="text"
                name="youtube_link"
                className="form-control"
                placeholder="Input YouTube Link..."
                onChange={this.handleLinkChange}
                value={this.state.link}
              />
              <p className="hint">Hint: https://www.youtube.com/watch?v=SOMETHING...</p>
            </div>
            <div className="col-md-2">
              <a href="https://www.youtube.com/" className="btn btn-to-youtube" target="_blank"><?php echo react_add_icon('youtube-play'); ?> YouTube</a>
            </div>
          </div>

          <FixedLabel label="YouTube Preview">
            <div id="youtube-preview" className="youtube-color youtube-border-color">
              {imagePreviewNode}
            </div>
          </FixedLabel>
        </div>
      );
    }
  });

  var CardExtensions = React.createClass({
    getInitialState: function() {
      if ("<?php echo $gantt_task_enabled; ?>" == "true") {
        return {
          hadGanttTaskAlready: "<?php echo $gantt_task['id']; ?>",
          ganttTaskEnabled: true,
          taskId:     "<?php echo $gantt_task['id']; ?>",
          taskName:   "<?php echo $card['title']; ?>",
          resource:   "<?php echo $template['name']; ?>",
          startDate:  "<?php echo $gantt_task['start_date']; ?>",
          endDate:    "<?php echo $gantt_task['end_date']; ?>",
          percentage:  parseInt(<?php echo $gantt_task['percentage']; ?>)
        };
      } else {
        return {
          hadGanttTaskAlready: 0,
          ganttTaskEnabled: false,
          taskId:     0,
          taskName:   "<?php echo $card['title']; ?>",
          resource:   "<?php echo $template['name']; ?>",
          startDate:  '',
          endDate:    '',
          percentage: 0
        };
      }
    },
    enableGanttTask: function(enabled) {
      if (enabled) {
        var selector = 'div#ganttChartFormNode';
        $(selector).fadeOut(500, 'swing', function() {
          this.setState({ ganttTaskEnabled: true });
          $(selector).slideDown(500, 'swing');
        }.bind(this));
      }
    },
    disableGanttTask: function(disabled) {
      if (disabled) {
        var selector = 'div#ganttChartFormNode';
        $(selector).slideUp(500, 'swing', function() {
          this.setState({ 
            ganttTaskEnabled: false,
            taskId: 0,
            taskName: "<?php echo $card['title']; ?>",
            resource: "<?php echo $template['name']; ?>",
            startDate: '',
            endDate: '',
            percentage: 0
          });
          $(selector).fadeIn(500, 'swing');
        }.bind(this));
      }
    },
    render: function() {
      var ganttChartFormNode;
      if (this.state.ganttTaskEnabled) {
        ganttChartFormNode = <GanttChartForm
          taskId={this.state.taskId}
          taskName={this.state.taskName}
          resource={this.state.resource}
          startDate={this.state.startDate}
          endDate={this.state.endDate}
          percentage={this.state.percentage}
          handleDisableGanttTask={this.disableGanttTask}
        />;
      } else {
        ganttChartFormNode = <EnableGanttChartBtn
          handleEnableGanttTask={this.enableGanttTask}
        />;
      }
      return (
        <div class="cardExtensions">
          <h4 className="form-section-title"><i className="fa fa-pencil"></i> Card Extensions</h4>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Gantt Chart Task</h4>
              <input
                type="hidden"
                name="had_gantt_task_already"
                value={this.state.hadGanttTaskAlready}
              />
            </div>
            
            <div className="col-md-8">
              <div id="ganttChartFormNode">
                {ganttChartFormNode}
              </div>
            </div>

          </div>
          
          <div className="gap-50"></div>

          <p className="hint text-center">More Feature Under Construction</p>
        </div>
      );
    }
  });

  var GanttChartForm = React.createClass({
    getInitialState: function() {
      return {
        taskId: this.props.taskId,
        taskName: this.props.taskName,
        resource: this.props.resource,
        startDate: this.props.startDate,
        endDate: this.props.endDate,
        percentage: this.props.percentage
      };
    },
    componentDidMount: function() {
      $('input[name="start_date"]').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '0d'
      }).on('changeDate', function(event) {
        var startDate = event.target.value;
        var endDate = this.state.endDate;
        if ((endDate == '') || (endDate >= startDate)) {
          this.setState({ startDate: startDate });
        } else {
          swal_pop('error', 'End date is not after the start date!');
          this.setState({ startDate: '' });
        }
      }.bind(this));
      $('input[name="end_date"]').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '0d'
      }).on('changeDate', function(event) {
        var startDate = this.state.startDate;
        var endDate = event.target.value;
        if ((startDate == '') || (startDate <= endDate)) {
          this.setState({ endDate: endDate });
        } else {
          swal_pop('error', 'Start date is not before the end date!');
          this.setState({ endDate: '' });
        }
      }.bind(this));
    },
    handleDisableGanttTask: function(event) {
      event.preventDefault();
      this.props.handleDisableGanttTask(true);
    },
    handlePercentageChange: function(event) {
      this.setState({ percentage: event.target.value });
    },
    handleAddPercentClick: function(event) {
      event.preventDefault();
      var percent = parseInt(this.state.percentage);
      if (percent === '') {
        this.setState({ percentage: 0 });
      } else if (percent == 100) {
        swal_pop('error', 'Max percentage is 100!');
      } else if (percent > 100) {
        this.setState({ percentage: 100 });
        swal_pop('error', 'Max percentage is 100!');
      } else {
        this.setState({ percentage: percent + 1 });
      }
    },
    handleMinusPercentClick: function(event) {
      event.preventDefault();
      var percent = parseInt(this.state.percentage);
      if (percent === '') {
        this.setState({ percentage: 100 });
      } else if (percent == 0) {
        swal_pop('error', 'Min percentage is 0!');
      } else if (percent < 0) {
        this.setState({ percentage: 0 });
        swal_pop('error', 'Min percentage is 0!');
      } else {
        this.setState({ percentage: percent - 1 });
      }
    },
    render: function() {
      return (
        <div className="ganttChartForm">
          
          <SubFixedLabel label="Task Name"><?php echo $card['title']; ?></SubFixedLabel>
          <SubFixedLabel label="Resource"><?php echo $template['name']; ?></SubFixedLabel>

          <div className="row">
            <div className="col-md-3 text-right">
              <h5 className="form-label">Start Date</h5>
            </div>
            <div className="col-md-4">
              <input
                type="text"
                name="start_date"
                value={this.state.startDate}
                className="form-control"
                placeholder="YYYY-MM-DD"
              />
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h5 className="form-label">End Date</h5>
            </div>
            <div className="col-md-4">
              <input
                type="text"
                name="end_date"
                value={this.state.endDate}
                className="form-control"
                placeholder="YYYY-MM-DD"
              />
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h5 className="form-label">Percentage (%)</h5>
            </div>
            <div className="col-md-2">
              <input
                type="text"
                name="percentage"
                value={this.state.percentage}
                onChange={this.handlePercentageChange}
                className="form-control"
                placeholder="0~100"
              />
            </div>
            <div className="col-md-1" id="add-percent-div">
              <button
                className="btn"
                id="add-percent-btn"
                onClick={this.handleAddPercentClick}
              ><span className="text-center">+</span></button>
            </div>
            <div className="col-md-1" id="minus-percent-div">
              <button
                className="btn"
                id="minus-percent-btn"
                onClick={this.handleMinusPercentClick}
              ><span className="text-center">-</span></button>
            </div>
          </div>

          <div className="row">
            <div className="col-md-7" id="disable-gantt-task">
              <button
                className="btn pull-right"
                onClick={this.handleDisableGanttTask}
              >Disable Gantt Chart Task</button>
              <input type="hidden" name="gantt_task_enabled" value="1" />
            </div>
          </div>

        </div>
      );
    }
  });

  var EnableGanttChartBtn = React.createClass({
    handleEnableGanttTaskClick: function(event) {
      event.preventDefault();
      this.props.handleEnableGanttTask(true);
    },
    render: function() {
      return (
        <div className="enableGanttChartBtn" id="enable-gantt-task">
          <button
            className="btn"
            onClick={this.handleEnableGanttTaskClick}
          >Enable Gantt Chart Task</button>
          <input type="hidden" name="gantt_task_enabled" value="0" />
        </div>
      );
    }
  });

  var SubFixedLabel = React.createClass({
    render: function() {
      return (
        <div className="subFixedLabel">
          <div className="row">
            <div className="col-md-3 text-right">
              <h5 className="form-label">{this.props.label}</h5>
            </div>
            <div className="col-md-4">
              <h5>{this.props.children}</h5>
            </div>
          </div>
        </div>
      );
    }
  });

  <?php $this->load->view(import_react_component('fixed_label')); ?>
  <?php $this->load->view(import_react_component('warning_message'), $warning_message); ?>

  function swal_pop(type, message) {
    var title;
    switch(type) {
      case 'success': title = 'Ok!'   ; break;
      case 'error':   title = 'Oops!' ; break;
    }
    swal({
      title: title,
      text: message,
      type: type,
      showConfirmButton: false,
      timer: 1500
    });
  }

  function get_youtube_image_link(key, size_type) {
    var image = null;
    switch(parseInt(size_type)) {
      case 1: image = 'default.jpg';       break;
      case 2: image = 'hqdefault.jpg';     break;
      case 3: image = 'mqdefault.jpg';     break;
      case 4: image = 'sddefault.jpg';     break;
      case 5: image = 'maxresdefault.jpg'; break;
    }
    return ('https://img.youtube.com/vi/' + key + '/' + image);
  }

  ReactDOM.render(
    <CardSettingForm
      url="<?php echo base_url(); ?>card/setting/<?php echo $card['id']; ?>"
      maxTitleLength="20"
      maxContentLength="200"
    />,
    document.getElementById('card-setting-form')
  );
</script>