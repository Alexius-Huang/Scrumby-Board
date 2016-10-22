<div class="row">
  <div class="col-md-offset-1 col-md-10">
    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h2><?php echo $card['title']; ?></h2>
        </div>
        <div class="card-content">
          <p><?php echo $card['content']; ?></p>
        </div>
        <div id="card-basic-info"></div>
        <div class="gap-20"></div>
      </div>
    </div>

    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h2><?php echo add_icon('tasks'); ?> Workspace</h2>
        </div>
        <div class="card-content">
          <p class="hint text-center"><?php echo add_icon('wrench'); ?> Workspace Feature Under Construction</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/babel">
  var CardBasicInfo = React.createClass({
    handleCardDetailClick: function(event) {
      event.preventDefault();
      var cssSelector_1 = "div.infoBlock";
      var cssSelector_2 = "div.embedContentBlock";
      if ($(cssSelector_1).css('display') == 'none') {
        $('a.view-info-trigger').html('<?php echo add_icon('sort-down'); ?> View Detail');
      } else {
        $('a.view-info-trigger').html('<?php echo add_icon('sort-up'); ?> View Detail');
      }
      $(cssSelector_1).slideToggle(500, 'swing', function() {
        if ($(cssSelector_2).css('display') == 'none') {
          $(cssSelector_2).slideDown(500);
        } else {
          $(cssSelector_2).slideUp(500);
        }
      });
    },
    componentDidMount: function() {
      $('div#display-loading').append('<p class="hint text-center"><?php echo add_icon('spinner'); ?> Loading Content...</p>');
      var cssSelector_1 = "div.infoBlock";
      var cssSelector_2 = "div.embedContentBlock";
      setTimeout(function() {
        $('div#display-loading').remove();
        $('a.view-info-trigger').html('<?php echo add_icon('sort-down'); ?> View Detail');
        $(cssSelector_1).slideDown(500, 'swing', function() {
          $(cssSelector_2).slideDown(500);
        });
      }, 1500);
    },
    render: function() {
      return (
        <div className="cardBasicInfo">
          <div className="row" id="view-info-btn">
            <div className="col-md-2">
              <div className="inner-card">
                <div className="card-block">
                  <a
                    href="#"
                    className="view-info-trigger"
                    onClick={this.handleCardDetailClick}
                  ><?php echo react_add_icon('sort-up'); ?> View Detail</a>
                </div>
              </div>
            </div>
          </div>
          <div className="gap-10"></div>
          <div id="display-loading"></div>
          <div id="slide-block" className="row">
            <div className="col-md-6">
              <InfoBlock />
            </div>
            <div className="col-md-6">
              <?php if ($card['type_id'] != '1'): ?>
                <EmbedContentBlock title="<?php echo $card['title'] ?>" ><?php echo $card['content']; ?></EmbedContentBlock>
              <?php endif; ?>
            </div>
          </div>
        </div>
      );
    }
  });

  var InfoBlock = React.createClass({
    render: function() {
      return (
        <div className="infoBlock inner-card" id="info-list">
          <div className="card-block">
            <h4>Card Basic Info</h4>
            <Info title="Card Opened By"><?php echo $card_opener; ?></Info>
            <Info title="Card Opened At"><?php echo timestamp_to_datetime($card['created_at']); ?></Info>
            <Info title="Card Type"><?php echo $card['type']; ?></Info>
            <div className="gap-20"></div>
            <h4>Project Basic Info</h4>
            <Info title="Project"><?php echo $project['title']; ?></Info>
            <Info title="Project Manager"><?php echo $manager; ?></Info>
            <Info title="Project Section"><?php echo $template['name']; ?></Info>
          </div>
        </div>
      );
    }
  });

  var Info = React.createClass({
    render: function() {
      return (
        <div className="info">
          <div className="row">
            <div className="col-md-4 text-right info-title">
              <h5>{this.props.title}</h5>
            </div>
            <div className="col-md-8 info-content">
              <h5>{this.props.children}</h5>
            </div>
          </div>
        </div>
      );
    }
  });

<?php if ($card['type_id'] != '1'): ?>
  var EmbedContentBlock = React.createClass({
    render: function() {
      return (
        <div className="embedContentBlock">
          <div className="row">
            <div className="col-md-offset-1 col-md-10">
            <?php if ($card['type_id'] == '2'): ?>
              <ImageBlock
                title={this.props.title}
                imageFile="<?php echo $image['file_name']; ?>"
                imageFileURL="<?php echo $image['file_path']; ?>"
              >{this.props.children}</ImageBlock>
            <?php elseif ($card['type_id'] == '3'): ?>
              <YoutubeBlock
                title={this.props.title}
                youtubeKey="<?php echo $youtube['key'] ?>"
                baseURL="<?php echo $youtube['base_url']; ?>"
                imageURL="<?php echo $youtube['image_link'][4]; ?>"
              >{this.props.children}</YoutubeBlock>
            <?php endif; ?>  
            </div>
          </div>
        </div>
      );
    }
  });
<?php endif; ?>

<?php if ($card['type_id'] == '2'): ?>
  var ImageBlock = React.createClass({
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
        <div className="imageBlock">
          <a href="#" onClick={this.handleShowImageContent}><img
            id="image-thumbnail"
            src={this.props.imageFileURL}
            alt={this.props.imageFile}
          /></a>
        </div>
      );
    }
  });
<?php elseif ($card['type_id'] == '3'): ?>
  var YoutubeBlock = React.createClass({
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
        <div className="youtubeBlock">
          <a href="#" onClick={this.handleOpenYoutubeVideo}>
            <img
              id="youtube-thumbnail"
              src={this.props.imageURL}
              alt="Youtube Preview"
            />
          </a>
        </div>
      );
    }
  });
<?php endif; ?>

  ReactDOM.render(
    <CardBasicInfo />,
    document.getElementById('card-basic-info')
  );
</script>