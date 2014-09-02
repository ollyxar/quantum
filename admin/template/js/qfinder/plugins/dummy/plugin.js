QFinder.addPlugin( 'dummy', {

	lang : [ 'en', 'pl' ],

	appReady : function( api ) {
		QFinder.dialog.add( 'dummydialog', function( api )
			{
				// QFinder.dialog.definition
				var dialogDefinition =
				{
					title : api.lang.dummy.title,
					minWidth : 390,
					minHeight : 230,
					onOk : function() {
						// "this" is now a QFinder.dialog object.
						var value = this.getValueOf( 'tab1', 'textareaId' );
						if ( !value ) {
							api.openMsgDialog( '', api.lang.dummy.typeText );
							return false;
						}
						else {
							alert( "You have entered: " + value );
							return true;
						}
					},
					contents : [
						{
							id : 'tab1',
							label : '',
							title : '',
							expand : true,
							padding : 0,
							elements :
							[
								{
									type : 'html',
									html : '<h3>' +  api.lang.dummy.typeText + '</h3>'
								},
								{
									type : 'textarea',
									id : 'textareaId',
									rows : 10,
									cols : 40
								}
							]
						}
					],
					buttons : [ QFinder.dialog.cancelButton, QFinder.dialog.okButton ]
				};

				return dialogDefinition;
			} );

		api.addFileContextMenuOption( { label : api.lang.dummy.menuItem, command : "dummycommand" } , function( api, file )
		{
			api.openDialog('dummydialog');
		});
	}
});
