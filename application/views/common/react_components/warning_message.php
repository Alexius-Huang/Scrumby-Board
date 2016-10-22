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