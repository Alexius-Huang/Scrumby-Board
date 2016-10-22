<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="card" id="new-card">
      <img src="<?php echo base_url(); ?>assets/images/NewCardBGI.jpg" alt="New Card BGI" class="card-image" />
      <div class="card-block">
        <div class="card-title">
          <h3>Create New Card</h3>
        </div>
        <div class="card-content">
          <div id="new-card-form"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/babel">
  var NewCardForm = React.createClass({
    componentDidMount: function() {
      $('.default-check').attr('checked', true);
    },
    render: function() {
      return (
        <form className="newCardForm" method="post" action={this.props.url} encType="multipart/form-data">

          <div className="gap-20"></div>
          <?php if ( ! empty($warning_message)) { echo '<WarningMessage />'; } ?>
          <div className="gap-20"></div>
          
          <hr />

          <BasicCardInfo
            maxTitleLength={this.props.maxTitleLength}
            maxContentLength={this.props.maxContentLength}
          />

          <hr />

          <CardExtensions />
          
          <hr />

          <div className="form-group">
            <input type="hidden" name="submitted" value="1" />
          </div>
          <button className="btn btn-submit pull-right">Submit</button>
          <div className="gap-50"></div>

        </form>
      );
    }
  });

  var BasicCardInfo = React.createClass({
    getInitialState: function() {
      return {
        'title': '',
        'content': '',
        'titleWordCount': this.props.maxTitleLength,
        'contentWordCount': this.props.maxContentLength
      };
    },
    handleTitleChange: function(event) {
      if (event.target.value.length <= this.props.maxTitleLength) {
        this.setState({
          'title': event.target.value,
          'titleWordCount': this.props.maxTitleLength - event.target.value.length
        });
      } else {
        swal({
          title: 'Oops!',
          text: 'Limited card title length is ' +  this.props.maxTitleLength +'!',
          showConfirmButton: true,
          confirmButtonColor: "#f55370",
          confirmButtonText: "OK!",
          type: 'error'
        });
      }
    },
    handleContentChange: function(event) {
      if (event.target.value.length <= this.props.maxContentLength) {
        this.setState({
          'content': event.target.value.replace(/(?:\r\n|\r|\n)/g, '\n'),
          'contentWordCount': this.props.maxContentLength - event.target.value.length
        });
      } else {
        swal({
          title: 'Oops!',
          text: 'Limited card content length is ' +  this.props.maxContentLength +'!',
          showConfirmButton: true,
          confirmButtonColor: "#f55370",
          confirmButtonText: "OK!",
          type: 'error'
        });
      }
    },
    render: function() {
      return (
        <div class="basicCardInfo">
          <h4 className="form-section-title"><i className="fa fa-pencil"></i> Basic Card Info</h4>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Card Title</h4>
            </div>
            <div className="col-md-8 text-left">
              <input
                type="text"
                name="title"
                className="form-control"
                placeholder="Enter your card title ..."
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
                onChange={this.handleContentChange}
                value={this.state.content}
                placeholder="Enter your card content ..."
              ></textarea>
              <p className="hint">{this.state.contentWordCount} Words Limited</p>
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Select Card Type</h4>
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
          <li><CardTypeRadioButton id="radio-1" value="1" class="default-check radio-btn">Plain Text</CardTypeRadioButton></li>
          <li><CardTypeRadioButton id="radio-2" value="2" class="radio-btn">Embed Image</CardTypeRadioButton></li>
          <li><CardTypeRadioButton id="radio-3" value="3" class="radio-btn">Embed YouTube Video</CardTypeRadioButton></li>
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
                  <EmbedImageContent />,
                  document.getElementById('embed-additional-content')
                );
                $(selector_2).slideDown(500, "swing");
              });
              break;
            case 3:
              $(selector_2).slideUp(500, "swing", function() {
                $(selector_2).html("");
                ReactDOM.render(
                  <EmbedYouTubeContent />,
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
      return { file: '', imagePreviewURL: '' };
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
        link: '',
        key: '',
        previewImageLink: ''
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
      return {
        startDate: '',
        endDate: '',
        percentage: 0,
        enableGanttChartTask: 0
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
    handleEnableGanttChartTask: function(event) {
      event.preventDefault();
      this.setState({ enableGanttChartTask: 1 });
      $('div#enable-gantt-task').fadeOut(500, function() {
        $('div#gantt-task-field').slideDown(500);
      });
    },
    handleDisableGanttChartTask: function(event) {
      event.preventDefault();
      this.setState({
        enableGanttChartTask: 0,
        startDate: '',
        endDate: '',
        percentage: 0
      });
      $('#gantt-task-field').slideUp(500, function() {
        $('#enable-gantt-task').fadeIn(500);
      });
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
        <div class="cardExtensions">
          <h4 className="form-section-title"><i className="fa fa-pencil"></i> Card Extensions</h4>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Gantt Chart Task</h4>
              <input
                type="hidden"
                name="gantt_chart_task"
                value={this.state.enableGanttChartTask}
              />
            </div>
            <div className="col-md-8" id="enable-gantt-task">
              <button
                className="btn"
                onClick={this.handleEnableGanttChartTask}
              >Setup Gantt Chart Task</button>
            </div>

            <div className="col-md-8" id="gantt-task-field">
              
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
                    onClick={this.handleDisableGanttChartTask}
                  >Disable Gantt Chart Task</button>
                </div>
              </div>

            </div>
          </div>
          
          <div className="gap-50"></div>

          <p className="hint text-center">More Feature Under Construction</p>
        </div>
      );
    }
  });

  <?php $this->load->view(import_react_component('warning_message'), $warning_message); ?>
  <?php $this->load->view(import_react_component('fixed_label')); ?>

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
    <NewCardForm
      url="<?php echo base_url(); ?>card/new_card/<?php echo $template_id; ?>"
      maxTitleLength="20"
      maxContentLength="200"
    />,
    document.getElementById('new-card-form')
  );
</script>