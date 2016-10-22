<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="card" id="project-management">
      <img src="<?php echo base_url(); ?>assets/images/ProjectManagementBGI.jpg" alt="Project Management BGI" class="card-image" />
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('wrench'); ?> Project Setting</h3>
        </div>
        <div class="card-content">
          <div id="project-manage-form"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/babel">
  var ProjectManageForm = React.createClass({
    render: function() {
      return (
        <div className="projectManageForm">
          <form method="post" action={this.props.url} id="form-submit" >
            <hr />
            <ProjectInfoField />
            <hr />
            <ProjectMemberField url={this.props.addProjectMemberURL} />
            <hr />
            <ProjectTemplateField url={this.props.addProjectTemplateURL} />
            <hr />
            <ButtonGroup />
            <div className="gap-50"></div>
          </form>
          <form method="post" action={this.props.url} id="form-delete-project" >
            <input type="hidden" name="delete_project" value="1" />
          </form>
        </div>
      );
    }
  });

  var ProjectInfoField = React.createClass({
    getInitialState: function() {
      return {
        title: "<?php echo $project['title']; ?>",
        description: "<?php echo br_to_newline($project['description']); ?>"
      };
    },
    handleTitleChange: function(event) {
      this.setState({ title: event.target.value });
    },
    handleDescriptionChange: function(event) {
      this.setState({ description: event.target.value.replace('\\n', '\n') });
    },
    render: function() {
      return (
        <div class="projectInfoField">
          <h4 className="form-section-title"><?php echo react_add_icon('pencil'); ?> Project Basic Info</h4>
          
          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Project Title</h4>
            </div>
            <div className="col-md-8 text-left">
              <input 
                type="text"
                id="project-title"
                name="title"
                className="form-control"
                placeholder="Enter your project title ..."
                onChange={this.handleTitleChange}
                value={this.state.title}
              />
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Project Description</h4>
            </div>
            <div className="col-md-8 text-left">
              <textarea
                name="content"
                id="project-content"
                rows="5"
                className="form-control"
                placeholder="Enter your project description ..."
                onChange={this.handleDescriptionChange}
                value={this.state.description}
              ></textarea>
            </div>
          </div>

        </div>
      );
    }
  });

  var ProjectMemberField = React.createClass({
    getInitialState: function() {
      var members = [];
      <?php foreach ($members as $member): ?>
        members.push({
          member_id: "<?php echo $member['id']; ?>",
          username: "<?php echo $member['username']; ?>",
          email: "<?php echo $member['email']; ?>"
        });
      <?php endforeach; ?>
      return { members: members, manager_email: "<?php echo $manager['email']; ?>" };
    },
    validateEmail: function(input) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(input);
    },
    handleAddMemberClick: function(event) {
      event.preventDefault();
      swal({
        title: 'Add Project Member',
        text: 'Enter the email of the contact.',
        type: 'input',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Add',
        cancelButtonText: 'Cancel',
        closeOnConfirm: false,
        closeOnCancel: true
      },
        function(inputValue) {
          if (inputValue == '') {
            swal_pop('error', 'The input field is empty!');
          } else if (this.validateEmail(inputValue)) {
            var checkDuplicatedMember = this.state.members.filter(function(member) {
              return ((member.email == inputValue) || (this.state.manager_email == inputValue));
            }.bind(this));
            if (checkDuplicatedMember.length != 0) {
              swal_pop('error', 'Member has already in your project!');
            } else {
              $.ajax({
                type: 'post',
                url: this.props.url,
                data: { add_contact_email: inputValue },
                dataType: 'json',
                success: function(data) {
                  if (data.contact == 'Not Found') {
                    swal_pop('error', 'Contact Not Found!');
                  } else {
                    swal({
                      title: 'Are you sure?',
                      text: data.contact.username + ' is going to be added to your project!',
                      type: 'info',
                      showConfirmButton: true,
                      showCancelButton: true,
                      confirmButtonText: 'Proceed',
                      cancelButtonText: 'Cancel',
                      closeOnConfirm: false,
                      closeOnCancel: true
                    },
                      function(confirmed) {
                        if (confirmed) {
                          var members = this.state.members;
                          members.push({
                            member_id: data.contact.id,
                            username: data.contact.username,
                            email: data.contact.email
                          });
                          this.setState({members: members});
                          swal({
                            title: 'Member Added!',
                            text: data.contact.username + ' has added into your project!',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1000
                          });
                        }
                      }.bind(this)
                    );
                  }
                }.bind(this),
                error: function(xhr, status, err) {
                  console.error(this.props.url, status, err.toString());
                }.bind(this)
              });
            }
          } else {
            swal_pop('error', 'Input is not in email format!');
          }
        }.bind(this)
      );
    },
    handleRemoveMember: function(member_id) {
      var members = this.state.members.filter(function(member) {
        return member.member_id != member_id;
      });
      this.setState({ members: members });
    },
    render: function() {
      return (
        <div className="projectMemberField">
          <h4 className="form-section-title"><?php echo react_add_icon('users'); ?> Project Members</h4>

          <FixedLabel label="Project Manager"><?php echo react_add_icon('user'); ?> <?php echo $manager['username']; ?></FixedLabel>
          <FixedLabel label="Manager Email"><?php echo react_add_icon('envelope'); ?> <?php echo $manager['email']; ?></FixedLabel>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Project Member List</h4>
            </div>
            <div className="col-md-8 text-left" id="member-list">
              <MemberList
                members={this.state.members}
                removeMember={this.handleRemoveMember}
              />
            </div>
          </div>
          
          <div className="row">
            <div className="col-md-offset-3 col-md-8">
              <button 
                className="btn btn-submit pull-right"
                id="add-member-btn"
                onClick={this.handleAddMemberClick}
              >Add Member</button>
            </div>
          </div>

        </div>
      );
    }
  });

  var MemberList = React.createClass({
    handleRemoveMember: function(member_id) {
      this.props.removeMember(member_id);
    },
    render: function() {
      var memberListNode = this.props.members.map(function(member) {
        return (
          <Member
            memberId={member.member_id}
            username={member.username}
            email={member.email}
            removeMember={this.handleRemoveMember}
          />)
      }.bind(this));
      return (
        <ul className="memberList">
          {memberListNode}        
        </ul>
      );
    }
  });

  var Member = React.createClass({
    handleRemoveProjectMember: function(event) {
      event.preventDefault();
      swal({
        title: 'Are you sure?',
        text: 'You are going to remove member ' + this.props.username + ' !',
        type: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Remove',
        cancelButtonText: 'Cancel',
        closeOnConfirm: false,
        closeOnCancel: true
      },
        function(confirmed) {
          if (confirmed) {
            swal({
              title: 'Member Removed!',
              text: 'You have removed ' + this.props.username + ' from the project!',
              type: 'success',
              showConfirmButton: false,
              timer: 1000
            });
            this.props.removeMember(this.props.memberId);
          }
        }.bind(this)
      );
    },
    render: function() {
      return (
        <div className="member">
          <li>
            <span><?php echo react_add_icon('user'); ?> {this.props.username}</span>
            <span><?php echo react_add_icon('envelope'); ?> {this.props.email}</span>
            <span><a
              href="#"
              className="remove-member-link"
              onClick={this.handleRemoveProjectMember}
            ><?php echo react_add_icon('remove') ?> Remove</a></span></li>
          <input type="hidden" name="member_ids[]" value={this.props.memberId} />
        </div>
      );
    }
  });

  var ProjectTemplateField = React.createClass({
    sortableTrigger: function() {
      var element = document.getElementById('template-form-list');
      var sortable = new Sortable(element, {
        handle: '.fa-bars'
      });
    },
    componentDidMount: function() {
      this.sortableTrigger();
      $('templateList').on('change', function() {
        this.sortableTrigger();
      });
    },
    render: function() {
      return (
        <div className="projectTemplateField">
          <h4 className="form-section-title"><?php echo react_add_icon('users'); ?> Project Templates</h4>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Project Templates</h4>
            </div>
            <div className="col-md-8 text-left" id="template-list">
              <TemplateList
              />
            </div>
          </div>

        </div>
      );
    }
  });

  var TemplateList = React.createClass({
    getInitialState: function() {
      var templates = [];
      <?php foreach ($templates as $template): ?>
        templates.push({
          templateId: "<?php echo $template['id']; ?>",
          name: "<?php echo $template['name']; ?>"
        });
      <?php endforeach; ?>
      return { templates: templates, newTemplateCount: 0 };
    },
    handleAddTemplateClick: function(event) {
      event.preventDefault();
      swal({
        title: 'Add Template',
        text: 'Enter the name of the new template.',
        type: 'input',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Add Template',
        cancelButtonText: 'Cancel',
        closeOnConfirm: false,
        closeOnCancel: true
      },
        function(inputValue) {
          if (inputValue == '') {
            swal_pop('error', 'Input value is empty! Please try again!');
          } else {
            swal_pop('success', 'Template ' + inputValue + ' added!');
            var count = this.state.newTemplateCount + 1;
            var templates = this.state.templates;
            templates.push({
              templateId: "new-" + count.toString(),
              name: inputValue
            });
            this.setState({
              templates: templates,
              newTemplateCount: count
            });
          }
        }.bind(this)
      );
    },
    render: function() {
      var templateNodes = this.state.templates.map(function(template) {
        return (<Template templateId={template.templateId}>{template.name}</Template>);
      }.bind(this));
      return (
        <div>
          <ul className="templateList" id="template-form-list">
            {templateNodes}
          </ul>
          <div className="row">
            <div className="col-md-offset-1 col-md-11">
              <button 
                className="btn btn-submit pull-right"
                id="add-template-btn"
                onClick={this.handleAddTemplateClick}
              >Add Template</button>
            </div>
          </div>
          <div id="remove-template"></div>
        </div>
      );
    }
  });

  var Template = React.createClass({
    getInitialState: function() {
      return { 'templateName': this.props.children, 'templateId': this.props.templateId };
    },
    handleEditClick: function(event) {
      event.preventDefault();
      swal({
        title: 'Edit Template',
        text: 'Input new template name.',
        type: 'input',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Change Name',
        cancelButtonText: 'Cancel',
        closeOnConfirm: false,
        closeOnCancel: true
      },
        function(inputValue) {
          if (inputValue == '') {
            swal_pop('error', 'Input value is empty! Please try again!');
          } else {
            swal_pop('success', 'Template name changed!');
            this.setState({ templateName: inputValue });
          }
        }.bind(this)
      );
    },
    handleRemoveClick: function(event) {
      event.preventDefault();
      swal({
        title: 'Are you sure?',
        text: 'You are removing template ' + this.state.templateName + ' !',
        type: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Remove Template',
        cancelButtonText: 'Cancel',
        closeOnConfirm: false,
        closeOnCancel: true
      },
        function(confirmed) {
          if (confirmed) {
            swal_pop('success', 'Template has been removed !');
            if (this.state.templateId.match(/new-([0-9]+)/) == null) {
              $('#remove-template').append('<input type="hidden" name="remove_template_ids[]" value="'+ this.state.templateId +'" />');
            }
            var selector = '#template-' + this.state.templateId;
            $(selector).remove();
          }
        }.bind(this)
      );
    },
    render: function() {
      return (
        <li className="templateItem" id={'template-' + this.state.templateId}>
          <p><i className="fa fa-bars" aria-hidden="true"></i> {this.state.templateName}</p>
          <a
            href="#"
            className="remove-template-link"
            onClick={this.handleRemoveClick}
          ><i className="fa fa-times" aria-hidden="true"></i> Remove</a>
          <a
            href="#"
            className="edit-template-link"
            onClick={this.handleEditClick}
          ><i className="fa fa-pencil" aria-hidden="true"></i> Edit</a>
          <input type="hidden" name="template_ids[]" value={this.state.templateId} />
          <input type="hidden" name="template_name[]" value={this.state.templateName} />
        </li>
      );
    }
  });

  var ButtonGroup = React.createClass({
    handleProjectDeleteClick: function(event) {
      event.preventDefault();
      var cancel = false;
      swal({
        title: 'Are you sure?',
        text: 'You are trying to delete the project!',
        type: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed.',
        cancelButtonText: 'No, please do not!',
        closeOnConfirm: false
      },
        function(confirmed) {
          if (confirmed) {
            swal({
              title: 'Security Check',
              text: 'Please enter the key of the project : <span oncopy="return false" id="project-key-validation" oncontextmenu="return false"><?php echo $project['key']; ?></span>',
              type: 'input',
              html: true,
              showConfirmButton: true,
              showCancelButton: true,
              confirmButtonText: 'Submit',
              cancelButtonText: 'Cancel',
              closeOnConfirm: false
            },
              function(inputValue) {
                if (inputValue != "<?php echo $project['key'] ?>") {
                  swal_pop('error', 'Wrong Project Key!');
                } else {
                  swal_pop('success', 'Your project has deleted! Redirecting...');
                  setTimeout(function() {
                    $("form#form-delete-project").submit();
                  }, 2000)
                }
              }.bind(this)
            );
          }
        }.bind(this)
      );
    },
    handleSaveChanges: function(event) {
      event.preventDefault();
      if (($('input#project-title').val().trim() == '') || ($('textarea#project-content').val().trim() == '')) {
        swal_pop('error', 'Project title or project description should not be empty ! Please try again !');
      } else {
        swal({
          title: 'Are you sure?',
          text: 'You are going to modify the project settings, data will be changed permanently!',
          type: 'info',
          showConfirmButton: true,
          showCancelButton: true,
          confirmButtonText: 'Save Changes',
          cancelButtonText: 'Cancel',
          closeOnConfirm: false,
          closeOnCancel: true
        },
          function(confirmed) {
            if (confirmed) {
              swal_pop('success', 'Saving Changes and Redirecting...');
              setTimeout(function() {
                $('#form-submit').submit();
              }.bind(this), 1000);
            }
          }.bind(this)
        );
      }
    },
    render: function() {
      return (
        <div class="buttonGroup">
          <div className="form-group">
            <input type="hidden" name="submitted" value="1" />
          </div>
          <button
            className="btn btn-delete pull-right"
            onClick={this.handleProjectDeleteClick}
          >Delete Project</button>
          <button
            className="btn btn-submit pull-right"
            onClick={this.handleSaveChanges}
          >Save Change</button>
        </div>
      );
    }
  });

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
      timer: 1000
    });
  }

  ReactDOM.render(
    <ProjectManageForm
      url="<?php echo base_url() ?>project/manage/<?php echo $project['id']; ?>"
      addProjectMemberURL="<?php echo base_url(); ?>ajax/project/get_contact_by_email"
    />,
    document.getElementById('project-manage-form')
  );
</script>