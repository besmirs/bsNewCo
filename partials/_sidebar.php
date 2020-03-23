<nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-home">
              <a class="nav-link" href="dashboard.php?page=home">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item nav-shop">
              <a class="nav-link" data-toggle="collapse" href="#ui-shops" aria-expanded="false" aria-controls="ui-shops">
                <span class="menu-title">Shops</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-store menu-icon"></i>
              </a>
              <div class="collapse" id="ui-shops">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=shops">Add new shop</a></li>
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=shops&action=edit">Modify/edit shops</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#shopassistants" aria-expanded="false" aria-controls="ui-services">
                <span class="menu-title">Shop assistants</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-home-assistant menu-icon"></i>
              </a>
              <div class="collapse" id="shopassistants">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=shopassistants">Add new shop</a></li>
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=shopassistants&action=edit">Modify/edit shops</a></li>
                </ul>
              </div>
            </li>            
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-services" aria-expanded="false" aria-controls="ui-services">
                <span class="menu-title">Services</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-server menu-icon"></i>
              </a>
              <div class="collapse" id="ui-services">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=services">Add new service</a></li>
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=services&action=edit">Modify services</a></li>
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=services&action=delete">Delete services</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-products" aria-expanded="false" aria-controls="ui-products">
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-sitemap menu-icon"></i>
              </a>
              <div class="collapse " id="ui-products">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=products">Add new product</a></li>
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=products&action=edit">Modify products</a></li>
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=products&action=delete">Delete products</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-customers" aria-expanded="false" aria-controls="ui-customers">
                <span class="menu-title">Customers</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-group menu-icon"></i>
              </a>
              <div class="collapse " id="ui-customers">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=customers">Add new customer</a></li>
                  <li class="nav-item"> <a class="nav-link" href="dashboard.php?page=customers&action=edit">Modify/edit customer</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item nav-home">
              <a class="nav-link" href="dashboard.php?page=sell">
                <span class="menu-title">Sell the product</span>
                <i class="mdi mdi-cart menu-icon"></i>
              </a>
            </li>                                     
          </ul>
        </nav>