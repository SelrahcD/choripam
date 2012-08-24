<?php require_once('../includes/config.php'); ?>
		<footer class="main-footer">
			<p>&copy; 2012 Chorip.am</p>
		</footer>
	</div>
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo $config['analytics-account']; ?>']);
	  _gaq.push(['_setDomainName', '<?php echo $config['analytics-domaine-name']; ?>']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</body>