				<nav class="top-bar" data-topbar>
					<ul class="title-area">
						<li class="name">
<?php						
	echo '<h1>'.HTML::anchor('Main', 'Hector').'</h1>';
?>							
						</li>
						<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
					</ul>

					<section class="top-bar-section">
						<ul class="left">
							<li class="divider"></li>
							<li class="has-dropdown">
								<a href="#">Collections</a>
								<ul class="dropdown">
<?php			
	echo '<li>'.HTML::anchor('Collection/view', 'Mine').'</li>';
?>									
								</ul>
							</li>
							<li class="divider"></li>
						</ul>
						<ul class="right">
							<li class="divider hide-for-small"></li>
							<li class="has-dropdown">
<?php
	echo HTML::anchor('#', Session::instance()->get('screen_name').' <b class="caret"></b>', array('role' => 'button', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown')) 
		.'<ul class="dropdown">'
		.'<li>'.HTML::anchor('User/sign_out', 'Sign Out').'</li>'
		.'</ul>'
	;
?>	
							</li>
						</ul>
					</section>
				</nav>
