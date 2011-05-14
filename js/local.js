YUI().use("yui", "tabview", "charts", 'io', "json-parse", function(Y) {
    var tabview = new Y.TabView({srcNode:'.dashboard'});tabview.render();
    
      var uri = "index.php/chart/index?n=10&i=1";
    Y.JSON.useNativeParse = false;
 
    function complete(id, o, args) { 
    data = Y.JSON.parse(o.responseText);

        var mychart = new Y.Chart({dataProvider:Y.JSON.parse(o.responseText), render:"#chart", categoryKey:"time"});
    };
 
    Y.on('io:complete', complete, Y);
 
    var request = Y.io(uri);
    
    
});