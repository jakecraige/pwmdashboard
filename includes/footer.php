
		</div> <!-- container div -->
		</div> <!-- end wrapper -->
		<div id="push"></div>
		<div id="footer">
			<div class="container">
				<p class="muted credit">
					<?php if(isset($_SESSION['user']) && $_SESSION['user'] != 'incomplete'): ?>
						Logged in as: <?php echo $_SESSION['user']; ?>
						| <a href="signout.php">Logout</a>
					<?php endif; ?>
					<span class="pull-right">
						&copy; <?php echo date('Y'); ?> PWM Inc
					</span>
				</p>
			</div>
		</div>
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="includes/js/bootbox.min.js"></script>
    <script src="includes/js/bootstrap.min.js"></script>
    <script>
        $(document).on("click", ".force_close", function(e) {
        	var r = document.getElementById('force_close');
        	var id = r.getAttribute('data-id');
        	delUrl = window.location.pathname + "?mode=close&id=" + id;
            bootbox.confirm("Are you sure you want to close this? (You will not be able to reopen it)", function(result) {
            	if(result == true) {
			  		window.location = delUrl;
           		}
			}); 
        });

        function confirmDelete(id) {
        	delUrl = window.location.pathname + "?mode=delete&id=" + id;
        	bootbox.confirm("Are you sure you want to delete this?", function(result) {
            	if(result == true) {
			  		window.location = delUrl;
            	}
			});
		}
	    </script>
	</body>
</html>