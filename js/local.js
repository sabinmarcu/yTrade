YUI({filter:'raw'}).use("yui", "tabview", function(Y) {
    var tabview = new Y.TabView({srcNode:'.dashboard'});
    tabview.render();
});