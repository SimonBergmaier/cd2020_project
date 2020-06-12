<?php use Blog\Util; ?>

<!--display error messages-->

<?php if (isset($errors) && is_array($errors)): ?>
<div class="errors alert alert-danger">
  <ul>
	  <?php foreach ($errors as $errMsg): ?>
        <li><?php echo(Util::escape($errMsg)); ?></li>
	  <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<!--/display error messages-->
<div class="footer">
	<hr />
	<div class="col-sm-4 pull-right">
		<p>
			<?php print date('r');  ?>
		</p>
	</div>
</div>
</div> <!-- container -->


</body>
</html>