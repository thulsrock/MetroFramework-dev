<?php

	$userDAO = new UserDAO();
	$userList = $userDAO->getList();
	$username = isset( $_GET['user-edit'] ) ? esc( $_GET['user-edit'] ) : NULL;

?>
	<div class='flex flex_row_reverse padding_bottom space_between'>
		<?php $this->buttonNewItem(); ?>
	</div>
	<div class='flex flex_column shadow round_border space_between nowrap light_grey_border border'>

		<?php
	
		$rowCount = 0;
		foreach ( $userList as $user ){
			$rowColor = ( $rowCount % 2 ) ? 'light_grey_background' : 'dark_grey_background';
			$rowCount++;
			
			$userURL = htmlspecialchars( $_SERVER['PHP_SELF'] ."?module=user&action=".USER_EDIT."&user=". esc( $user->ID ) );
			$deleteUserUrl = htmlspecialchars( $_SERVER["REQUEST_URI"]."&action=".USER_DELETE."&user=" ) . $user->ID;
			?>
			<div class='flex flex_row light_blue_background_hover padding <?php echo esc( $rowColor ); ?>'>
				<a class='flex flex_row full_width' href="<?php echo $userURL; ?>" >
					<div style='width: 25%;' data-header="Cognome" ><?php echo esc( $user->surname ); ?></div>
					<div style='width: 25%;' data-header="Nome" ><?php echo esc( $user->name ); ?></div>
					<div style='width: 25%;' data-header="Cartellino" ><?php echo esc( $user->serialNumber); ?></div>
					<div class='full_width' data-header="email" ><?php echo esc( $user->email ); ?></div>
				</a>
				<a class='red_text_hover block zoom' href="<?php echo $deleteUserUrl; ?>" title="Elimina utente"><i class="material-icons align_middle">&#xE14C;</i></a>
			</div>
	<?php } ?>
	</div>
</div>