
<?php $this -> load -> helper('url'); ?>
<!DOCTYPE html>
<head>
    <title>yTrade | <?php echo isset($title) ? $title : "Home" ?></title>
    <link href='<?php echo base_url() ?>css/screen.css' rel='stylesheet' media="screen" />
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.3.0/build/cssgrids/grids-min.css">
    <script type='text/javascript' src='<?php echo base_url() ?>js/yui-min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>js/local.js'></script>
</head>
<body>
    <header>
        <section id='logo'>  
            <a href='/'>
                   <img src='<?php echo base_url() ?>images/logo.png' />
            </a>
        </section>
        <section id='user-status'>
                <ul id='main'><li><span class='type'>FUNDS</span><span class='ammount'>1</span></li></ul>
                <ul id='currencies'>
                    <li><span class='type'>GBP</span><span class='ammount'>150</span></li>
                    <li><span class='type'>AUD</span><span class='ammount'>50</span></li>                
                    <li><span class='type'>USD</span><span class='ammount'>25</span></li>
                    <li><span class='type'>RON</span><span class='ammount'>35</span></li>
                    <li><span class='type'>CHF</span><span class='ammount'>125</span></li>
                </ul>
                <div class='dropdown'>Hover for more information.</div>
        </section>
        <section id='user-account'>
            <ul>
               <li>Hi, <b><?php echo $this->session->userdata('username'); ?></b></li>
               <li><?php echo anchor('/user/logout', 'Logout') ?></li>
                <li><a href='#'>Settings</a></li>
            </ul>
        </section>
    </header>
    <div class='dashboard'>
        <ul>
            <li><a href='#' id='dashboard'>Dashboard</a></li>
            <li><a href='#' id='bavailable'>Current Sales</a></li>
            <li><a href='#' id='bmine'>My Transactions</a></li>
            <li><a href='#' id='statistics'>Statistics</a></li>
        </ul>
        <div>
    <article id='dashboard'>