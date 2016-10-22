<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="card" id="user-account">
      <img src="<?php echo base_url(); ?>assets/images/AccountSettingBGI.jpg" alt="AccountSetting BGI" id="account-setting-bgi" class="card-image" />
      <div class="card-block">
        <div class="card-title">
          <h3><?php echo add_icon('cogs'); ?> Account Settings</h3>
        </div>
        <div class="card-content">
          <div id="user-account-form"></div> 
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 
<hr />

<h4 className="form-section-title"><i className="fa fa-pencil"></i> My Contact</h4>

<p className="hint text-center"><i className="fa fa-wrench"></i> Under Construction</p>

<hr />

<h4 className="form-section-title"><i className="fa fa-pencil"></i> Theme</h4>

<p className="hint text-center"><i className="fa fa-wrench"></i> Under Construction</p>
-->

<script type="text/babel">
  var UserAccountForm = React.createClass({
    render: function() {
      return (
        <form action={this.props.url} method="post" className="userAccountForm">
          
          <?php if ( ! empty($warning_message)): ?>
            <div className="gap-20"></div>
            <WarningMessage />
            <div className="gap-20"></div>
          <?php elseif ($this->session->userdata('success_message') != NULL): ?>
            <?php $success_message = $this->session->userdata('success_message'); ?>
            <div className="gap-20"></div>
            <SuccessMessage />
            <?php $this->session->unset_userdata('success_message'); ?>
            <div className="gap-20"></div>
          <?php endif; ?>
          
          <hr />

          <h4 className="form-section-title"><i className="fa fa-pencil"></i> My Account</h4>

          <AccountForm maxUsernameLength={this.props.maxUsernameLength} />

          <hr />

          <h4 className="form-section-title"><i className="fa fa-pencil"></i> My Profile</h4>

          <p className="hint text-center"><i className="fa fa-wrench"></i> Under Construction</p>
          
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

  var AccountForm = React.createClass({
    getInitialState: function() {
      return {
        'email': "<?php echo $this->session->email; ?>",
        'username': "<?php echo $this->session->username; ?>",
        'pswd': '',
        'pswdConfirmation': '',
        'maxUsernameLength': this.props.maxUsernameLength
      };
    },
    handleEmailChange: function(event) {
      this.setState({'email': event.target.value});
    },
    handleUsernameChange: function(event) {
      this.setState({'username': event.target.value});
    },
    handlePswdChange: function(event) {
      this.setState({'pswd': event.target.value});
    },
    handlePswdConfirmationChange: function(event) {
      this.setState({'pswdConfirmation': event.target.value})
    },
    render: function() {
      return (
        <div className="accountForm">
          
          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Email</h4>
            </div>
            <div className="col-md-8 text-left">
              <input
                type="text"
                name="email"
                className="form-control"
                placeholder="Enter your email name ..."
                onChange={this.handleEmailChange}
                value={this.state.email}
              />
            </div>
          </div>
          
          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">User Name</h4>
            </div>
            <div className="col-md-8 text-left">
              <input
                type="text"
                name="username"
                className="form-control"
                placeholder="Enter your user name ..."
                onChange={this.handleUsernameChange}
                value={this.state.username}
              />
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Password</h4>
            </div>
            <div className="col-md-8 text-left">
              <input
                type="password"
                name="password"
                className="form-control"
                placeholder="Enter your password ..."
                onChange={this.handlePswdChange}
                value={this.state.pswd}
              />
            </div>
          </div>

          <div className="row">
            <div className="col-md-3 text-right">
              <h4 className="form-label">Password Confirmation</h4>
            </div>
            <div className="col-md-8 text-left">
              <input
                type="password"
                name="password_confirmation"
                className="form-control"
                placeholder="Enter your password again ..."
                onChange={this.handlePswdConfirmationChange}
                value={this.state.pswdConfirmation}
              />
            </div>
          </div>

          <div className="row">
            <div className="col-md-offset-3 col-md-8">
              <p className="hint">Hint: Leave the password and password confirmation blank if you don't want to change your password.</p>
            </div>
          </div>

        </div>
      );
    }
  });

  <?php $this->load->view(import_react_component('warning_message'), $warning_message); ?>

  var SuccessMessage = React.createClass({
    render: function() {
      return (
        <div className="successMessage">
          <div className="row">
            <div className="col-md-offset-2 col-md-8">
              <h5><i className="fa fa-check" aria-hidden="true"></i> <?php echo $success_message; ?></h5>
            </div>
          </div>
        </div>
      );
    }
  });

  ReactDOM.render(
    <UserAccountForm 
      url="<?php echo base_url(); ?>user/setting/<?php echo $this->session->userdata['id']; ?>"
      maxUsernameLength="30"
    />,
    document.getElementById('user-account-form')
  );
</script>