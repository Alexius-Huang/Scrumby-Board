
<div class="gap-70"></div>

<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <img class="main-title-img" src="<?php echo base_url(); ?>assets/images/ScrumbyBoardTitle.png" alt="Scrumby Board Main Title">
  </div>
</div>

<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div id="content"></div>
  </div>
</div>

<!-- 
  
  ReactDOM Design for user_authentication/signin_view

    - Main
      - Header
      - WarningMessage
      - Form

-->

<script type="text/babel">
  var SignIn = React.createClass({
    render: function() {
      return (
        <div className="signIn">
          <SignInHeader />
          <div className="gap-20"></div>
          <?php if ( ! empty($warning_message)) { echo '<WarningMessage />'; } ?>
          <div className="gap-20"></div>
          <SignInForm />
        </div>
      );
    }
  });

  var SignInHeader = React.createClass({
    render: function() {
      return (
        <div className="signInHeader">
          <h2><?php echo $title; ?></h2>
        </div>
      );
    }
  });

  var WarningMessage = React.createClass({
    render: function() {
      return (
        <div className="warningMessage">
          <div className="row">
            <div className="col-md-offset-2 col-md-8">
              <h5><i className="fa fa-times" aria-hidden="true"></i> <?php echo $warning_message; ?></h5>
            </div>
          </div>
        </div>
      );
    }
  });

  var SignInForm = React.createClass({
    getInitialState: function() {
      return { 'email': '', password: '' };
    },
    handleEmailChange: function(event) {
      this.setState({ 'email': event.target.value });
    },
    handlePasswordChange: function(event) {
      this.setState({ 'password': event.target.value });
    },
    render: function() {
      return (
        <form className="signInForm" method="post" action="/user_authentication/signin/">
          <div className="row">
            <div className="col-md-offset-2 col-md-3">
              <input
                type="text"
                name="email"
                className="form-control"
                placeholder="Your Email ..."
                onChange={this.handleEmailChange}
                value={this.state.email}
              />
            </div>
            <div className="col-md-3">
              <input
                type="password"
                name="password"
                placeholder="Your Password ..."
                className="form-control"
                onChange={this.handlePasswordChange}
                value={this.state.password}
              />
            </div>
            <div className="col-md-2">
              <button 
                type="submit"
                className="btn btn-default btn-submit"
              >Submit</button>
            </div>
          </div>
        </form>
      );
    }
  });

  ReactDOM.render(
    <SignIn />,
    document.getElementById('content')
  );
</script>