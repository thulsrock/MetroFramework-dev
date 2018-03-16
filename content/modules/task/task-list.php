<?php
	$taskDAO = new TaskDAO();
	$departmentDAO = new DepartmentDAO();
	$taskList = [];
	
	if( isset( $_GET['department'] ) && isset( $_GET['target'] ) ) {
		$taskList = $taskDAO->getList( $_GET['department'], $_GET['target'] );
	} elseif ( $this->session->hasPrivilege( TASK_MONITOR ) ) {
		$taskList = $taskDAO->getList();
	} else {
		$deps = [];
		$activeUserJobs = $this->session->getActiveUserJobs();
		foreach( $activeUserJobs as $activeUserJob ) {
			if( isset( $activeUserJob->features ) ) {
				foreach ( $activeUserJob->features as $feature ) {
					if( $feature == 'task-view' ) {
						$deps[] = $activeUserJob->department;
					}
				}
			}
		}
		$taskList = $taskDAO->getList( $deps );
	}
?>
	<div class='flex flex_row padding_bottom space_between'>
		<div class="bigger_font">
			<?php 
				if( isset( $_GET['department'] ) && isset( $_GET['target'] ) ) {
					$depNameFromID = $departmentDAO->getNameFromID( $_GET['department'] );
			?>	
				<a class="underline_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] . "?module=target&department=" . $_GET['department']); ?>" title="<?php echo TARGETS_BY_DEPARTMENT; ?>"><?php echo htmlentities( $depNameFromID, ENT_QUOTES ); ?></a>
			<?php } ?>
		</div>
		
		<?php $this->buttonNewItem(); ?>
	</div>
	<div class='task_list shadow round_border space_between nowrap border light_grey_border'>
		<?php if( isset( $_GET['target'] ) ) { ?>		
			<div class='flex flex_column padding'>Obiettivo: <?php echo htmlentities( $_GET['target'] ); ?></div>
		<?php 
		} if( empty ( $taskList) ) { ?>
			<div class="padding">Non sono presenti attività.</div>
		<?php 
			return;
		} else {
			$rowCount = 0;
			foreach ( $taskList as $task ) {
				$depName = $departmentDAO->getNameFromID( $task->department );
				$rowColor = ( $rowCount % 2 ) ? 'light_grey_background' : 'dark_grey_background';
				$indicatorDAO = new IndicatorDAO();
				if( $indicatorDAO->isComplete( $task->target, $task->code ) ) $rowColor = ' progressCompleteColor';
				$rowCount++;
				?>
			<div class="flex flex_row">
				<div>	
					<a href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ."?module=indicator&action=open&target=". $task->target . '&code=' . $task->code ); ?>" title="Visualizza indicatore" >	
						<div class="flex flex_row middle_blue_background_hover wrap transition space_between padding <?php echo esc( $rowColor ); ?>">
							<?php if( !isset($depNameFromID) ) { ?>
							<div class='flex flex_row padding full_width bigger_font'>
								<?php echo htmlentities( $depName, ENT_QUOTES ); ?>
							</div>
							<?php } ?>
							<div class='flex flex_row padding fair_grow'>
								<label class='big_right_margin'>Attività</label>
								<div class='align_center'><?php echo htmlentities( $task->target . $task->code, ENT_QUOTES ); ?></div>
							</div>				
							<div class='flex flex_row padding fair_grow'>
								<label class='big_right_margin'>Inizio</label>
								<div><?php echo htmlentities( $task->startDate, ENT_QUOTES ); ?></div>
							</div>				
							<div class='flex flex_row padding fair_grow'>
								<label class='big_right_margin'>Scadenza</label>
								<div><?php echo htmlentities( $task->endDate, ENT_QUOTES );?></div>
							</div>	
							<div class='flex flex_row padding fair_grow'>
								<label class='big_right_margin'>Risultato atteso</label>
								<div><?php echo htmlentities( $task->attendedResult, ENT_QUOTES );?>%</div>
							</div>				
							<div class='flex flex_row padding fair_grow'>
								<label class='big_right_margin'>Risultato ottenuto</label>
								<div><?php echo htmlentities( $task->actualResult, ENT_QUOTES );?>%</div>
							</div>
							<div class='flex flex_column padding'>
	    						<label style="padding-bottom: 10px;">Descrizione</label>
	    						<div class="justify"><?php echo htmlentities( $task->description, ENT_QUOTES ); ?></div>
	    					</div>		
						</div>
					</a>
				</div>
				
				<?php if( $this->session->hasPrivilege(TASK_EDIT) ) { ?>
					<div class="flex flex_column <?php echo esc( $rowColor ); ?>">
						<a style="height: 50%; width: 60px;" class="flex align_middle align_center dark_blue_text_hover zoom" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ."?module=task&action=".TASK_EDIT."&target=". $task->target . '&code=' . $task->code  ); ?>" title="Visualizza scheda attività">
							<i class="material-icons transition">zoom_in</i>
						</a>
					 	<a style="height: 50%; width: 60px;" class="zoom red_text_hover" href="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ."?module=task&action=".TASK_DELETE."&target=". $task->target . '&code=' . $task->code  ); ?>" title="Elimina">
							<div class="flex align_middle full_height align_center">
								<i class="material-icons">delete</i>
							</div>
						</a>
					</div>
				<?php } ?>
			</div>
			<?php } ?>
		<?php } ?>
	</div>