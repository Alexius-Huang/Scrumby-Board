var FixedLabel = React.createClass({
  render: function() {
    return (
      <div className="row fixedLabel">
        <div className="col-md-3 text-right">
          <h4 className="form-label">{this.props.label}</h4>
        </div>
        <div className="col-md-8 text-left">
          <h4>{this.props.children}</h4>
        </div>
      </div>
    );
  }
});