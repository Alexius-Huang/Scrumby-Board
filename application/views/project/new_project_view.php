<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="card" id="new_project">
      <img src="<?php echo base_url(); ?>assets/images/NewProjectBGI.png" alt="New Project BGI" id="new_project_bgi" class="card-image" />
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('cube'); ?> Start New Project</h3>
        </div>
        <div class="card-content">
          <div id="new-project-form"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/babel">
  var NewProjectForm = React.createClass({
    getInitialState: function() {
      return {'title': '', 'description': ''};
    },
    sortableTrigger: function() {
      var element = document.getElementById('template-form-list');
      var sortable = new Sortable(element, {
        handle: '.fa-bars'
      });
    },
    handleTitleChange: function(event) {
      this.setState({'title': event.target.value});
    },
    handleDescriptionChange: function(event) {
      this.setState({'description': event.target.value.replace(/(?:\r\n|\r|\n)/g, '\n')});
    },
    componentDidMount: function() {
      this.sortableTrigger();
      $('.templateForm').on('change', function() {
        this.sortableTrigger();
      });
    },
    render: function() {
      return (
        <form method="post" action={this.props.url} className="newProjectForm">
          
          <hr />

          <h4 className="form-section-title"><?php echo react_add_icon('pencil'); ?> Project Basic Info</h4>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Project Name</h4>
            </div>
            <div className="col-md-8 text-left">
              <input 
                type="text"
                name="title"
                className="form-control"
                placeholder="Enter your project name ..."
                onChange={this.handleTitleChange}
                value={this.state.title}
              />
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Description</h4>
            </div>
            <div className="col-md-8 text-left">
              <textarea
                name="description"
                className="form-control"
                placeholder="Enter the description of your project ..."
                rows="5"
                onChange={this.handleDescriptionChange}
                value={this.state.description}
              ></textarea>
            </div>
          </div>

          <hr />
          
          <h4 className="form-section-title"><?php echo react_add_icon('tasks'); ?> Setup Project Scrumby Board Templates</h4>

          <TemplateForm />

          <hr />

          <h4 className="form-section-title"><?php echo react_add_icon('users'); ?> Invite Project Cooperators</h4>

          <ProjectMemberForm />

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

  var TemplateForm = React.createClass({
    getInitialState: function() {
      var templateNames = [];
      var templateCount = 0;
      <?php foreach ($default_templates as $template_id => $template): ?>
        templateNames.push("<?php echo $template; ?>");
        templateCount++;
      <?php endforeach; ?>
      return {'templateNames': templateNames, 'templateCount': templateCount};
    },
    addTemplate: function(event) {
      event.preventDefault();
      swal({
        title: "Add New Template",
        text: "Enter Template Name",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Name of the template ..." 
      },
      function(inputValue){
        if (inputValue === false)
          return false;
        if (inputValue === "") { 
          swal.showInputError("Empty value, please name your template!");
          return false;
        }
        var templateNames = this.state.templateNames;
        var templateCount = this.state.templateCount;
        templateNames.push(inputValue);
        templateCount++;
        this.setState({'templateNames': templateNames, 'templateCount': templateCount });
        this.render();
        swal({
          title: 'Ok!',
          text: 'Template ' + inputValue + ' has successfully added!',
          type: 'success',
          timer: 1000,
          showConfirmButton: false
        });
      }.bind(this));
    },
    render: function() {
      var templateItemNodes = this.state.templateNames.map(function(name) {
        return (<TemplateItem templateId="">{name}</TemplateItem>)
      });
      return (
        <div className="templateForm">
          <ul>
            <div id="template-form-list">
              {templateItemNodes}
            </div>
          </ul>
          <a
            href="#"
            className="btn btn-template-form"
            id="add-template-btn"
            onClick={this.addTemplate}
          >Add Template</a>
        </div>
      );
    }
  });

  var TemplateItem = React.createClass({
    getInitialState: function() {
      return { 'templateName': this.props.children };
    },
    handleEditClick: function(event) {
      event.preventDefault();
      swal({
        title: "Edit Template",
        text: "Change Template Name",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Name of the template ..." 
      },
      function(inputValue){
        if (inputValue === false)
          return false;
        if (inputValue === "") { 
          swal.showInputError("Empty value, please rename your template!");
          return false;
        }
        this.setState({'templateName': inputValue});
        swal({
          title: 'Ok!',
          text: 'Template has successfully edited!',
          type: 'success',
          timer: 1000,
          showConfirmButton: false
        });
      }.bind(this));
    },
    handleRemoveClick: function(event) {
      event.preventDefault();
      swal({
        title: "Are you sure?",
        text: "Delete this template?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      },
      function() {
        ReactDOM.findDOMNode(this).remove();
        swal({
          title: 'Deleted!',
          text: 'Template has successfully deleted!',
          type: 'success',
          timer: 1000,
          showConfirmButton: false
        });
      }.bind(this));
    },
    render: function() {
      return (
        <li className="templateItem">
          <p><i className="fa fa-bars" aria-hidden="true"></i> {this.state.templateName}</p>
          <a
            href="#"
            className="btn btn-template-item"
            onClick={this.handleEditClick}
          ><i className="fa fa-pencil" aria-hidden="true"></i> Edit</a>
          <a
            href="#"
            className="btn btn-template-item"
            onClick={this.handleRemoveClick}
          ><i className="fa fa-times" aria-hidden="true"></i> Remove</a>
          <input type="hidden" name="template_order[]" value={this.state.templateName} />
        </li>
      );
    }
  });
  var ProjectMemberForm = React.createClass({
    render: function() {
      return (
        <div className="projectMemberForm">
          <?php foreach ($members as $member): ?>
            <div className="row">
              <div className="col-md-offset-1 col-md-1">
                <ProjectMemberCheckbox value="<?php echo $member['id']; ?>" />
              </div>
              <div className="col-md-3 member-<?php echo $member['id']; ?>">
                <i className="fa fa-user" aria-hidden="true"></i> <?php echo $member['username']; ?>
              </div>
              <div className="col-md-6 member-<?php echo $member['id']; ?>">
                <i className="fa fa-envelope" aria-hidden="true"></i> <?php echo $member['email']; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      );
    }
  });

  var ProjectMemberCheckbox = React.createClass({
    handleChange: function(event) {
      var query = ".member-" + this.props.value; 
      if (event.target.checked) {
        $(query).css({
          'color': '#5e91b6',
          'transition': 'color 0.5s'
        });
      } else {
        $(query).css({
          'color': 'black',
          'transition': 'color 0.5s'
        });
      }
    },
    render: function() {
      return (
        <input
          type="checkbox"
          className="projectMemberCheckbox pull-right"
          name="member[]"
          value={this.props.value}
          onChange={this.handleChange}
        />
      );
    }
  });

  ReactDOM.render(
    <NewProjectForm url="/project/new_project" />,
    document.getElementById('new-project-form')
  );
</script>