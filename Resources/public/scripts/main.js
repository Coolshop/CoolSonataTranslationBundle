(function($){
	$(document).on('ready ajaxComplete',function(){
		var $formTable = $('#coolSonataTransPlaceholder').siblings('form').find('table');
		$formTable.table_scroll({
			fixedColumnsLeft: 4,
			columnsInScrollableArea: 5,
			rowsInFooter: 2,
			rowsInHeader:1,
			scrollX: 0,
			scrollY: 0,
			rowsInScrollableArea:500,
			overflowY:'auto',
		});
		$formTable.find('thead').append(
			$formTable.find('tfoot tr:last-child')
		);
	});
})(jQuery);