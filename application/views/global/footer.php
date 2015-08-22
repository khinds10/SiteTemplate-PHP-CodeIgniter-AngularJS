        <div ng-controller='defaultModalController'><!--do not remove this modals included here -->
	        <?php
		        if (isset($modalWindowsByURLMapping)) {
		        	foreach ($modalWindowsByURLMapping as $modalMapped) {
	        ?>
	            <modal-dialog show='modalData.modalShown' on-close='dialogClosed()' track-modal-shown-event='<?=htmlentities(json_encode($modalMapped['trackingEvent']));?>' show-modal-by-url-params='<?=$modalMapped['showModalParams']?>'>
	                <?=isset($this) ? $this->load->view($modalMapped['modalWidowPartial']) : ''; ?>
	        	</modal-dialog>
	        <?php
		        	}
		        }
	        ?>
        </div>
        	<footer class="row">
				<div class="large-12 columns">
					<hr />
					<div class="row">
						<div class="large-6 columns">
							<p>&copy; <?=WEBSITE_NAME?></p>
						</div>
						<div class="large-6 columns">
							<ul class="inline-list right">
								<li><a href='/'>Home</a></li>
								<li><a href='/about'>About</a></li>
								<li><a href='/faqs'>FAQs</a></li>
								<li><a href='?msg=thank-you'>Modal Window via URL</a></li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
        </body>
</html>