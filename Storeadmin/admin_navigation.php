<?php include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/user.php"; ?>
<?php include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/messagecount.php"; ?>
<?php include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/clientcount.php"; ?>
<?php include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/ordercount.php"; ?>
<?php include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/reorder_product_count.php"; ?>
<?php include "/home/shamrang/zeus.shamrangouse.com/Storeadmin/StoreScripts/products_count.php"; ?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>

<script>
$("ul").click(function() {
    $(this).find("li").css("display", "block");
    return false;
});
    
$(function() {
     var pgurl = window.location.href.substr(window.location.href
.lastIndexOf("/")+1);
     $("#nav ul li a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
          $(this).addClass("active");
     })
});
</script>



<!-- Custom Header Font -->
<link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>

<!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" style="color:#fed136;" href="index.php"> Zeus Admin</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav" style="padding-right: 5%;">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $user['username']; ?> <b class="caret"></b>
                        
                            <?php 
                            $allcount = "";
                            
                            $allcount = $Ordercount + $reordercount + $messagecount;
                            
                            if($allcount > 0){
                                
                                echo '<small class="badge pull-right" style="background-color: #fc3; color: #000; margin-left: 8px;">'.$allcount.'</small>';
                            }
                            ?>
                        
                    
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="AllOrders.php"><i class="fa fa-fw fa-shopping-cart"></i> Orders
                                <small class="badge pull-right" style="background-color: #fc3; color: #000;"><?php print("$Ordercount"); ?></small>
                            </a>
                        </li>
                        
                        <li>
                            <a href="AllProducts.php"><i class="fa fa-fw fa-tags"></i> Re-Order
                                <small class="badge pull-right" style="background-color: #fc3; color: #000;"><?php print("$reordercount"); ?></small>
                            </a>
                        </li>
                        <li>
                            <a href="mailbox.php"><i class="fa fa-fw fa-envelope"></i> Inbox
                                <small class="badge pull-right" style="background-color: #fc3; color: #000;"><?php print("$messagecount"); ?></small>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/Storeadmin/StoreScripts/admin_logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div id="nav" class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> &nbsp; Dashboard</a>
                    </li>
                    <li>
                        <a href="AllOrders.php"><i class="fa fa-fw fa-shopping-cart"></i> &nbsp; Orders 
						<small class="badge pull-right" style="background-color: #fc3; color: #000;"><?php print("$Ordercount"); ?></small>
						</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo">
                            <i class="fa fa-fw fa-tags"></i>&nbsp; Products <i class="fa fa-fw fa-caret-down"></i>
                            <small class="badge pull-right" style="background-color: #fc3; color: #000;"><?php print("$reordercount"); ?></small>
                        </a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="Add_products.php"><i class="fa fa-fw fa-plus-square"></i> &nbsp; Add Products </a>
                            </li>
							<li>
                                <a href="AllProducts.php">
                                    <i class="fa fa-fw fa-search"></i> &nbsp; All Products 
                                    <small class="badge pull-right" style="background-color: #fc3; color: #000;"><?php print("$reordercount"); ?></small>
                                </a>
                            </li>
                        </ul>
                    </li>
					
					<li>
                        <a href="Allclients.php"><i class="fa fa-users"></i> &nbsp; Clients </a>
                    </li>
					
					<li>
                        <a href="search_product.php"><i class="fa fa-fw fa-search"></i> &nbsp; Search </a>
                    </li>
					
                    <li>
                        <a href="Create_Admin.php"><i class="fa fa-fw fa-user-plus"></i> &nbsp; Create Admin User </a>
                    </li>
					
					<li>
						<a href="mailbox.php">
							<i class="fa fa-envelope"></i>  &nbsp;  Messages
							<small class="badge pull-right" style="background-color: #fc3; color: #000;"><?php print("$messagecount"); ?></small>
						</a>
                    </li>
					
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>