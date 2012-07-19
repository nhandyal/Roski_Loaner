				</head>
				<body>
						<div id="page">
								<p id='error'> </p>
								<div id="header">
										<?php
												//show menu when user is logged in
												if(isset($_SESSION['user'])){
														require_once("menubar.php");
												}
												else{ ?>
														<a href='/loaner/' style='text-decoration:none; color:black'><h1>Loaner :: Roski School of Fine Arts</h1></a>
														<?php
												} ?>
								</div>