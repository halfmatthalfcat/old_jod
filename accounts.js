// JavaScript Document
$(document).ready(function(){
	$.get("getAccounts.php", function(data){
		editableGrid = new EditableGrid("Accounts");
		editableGrid.loadJSONFromString(data);
		editableGrid.setCellRenderer("action", new CellRenderer({render: function(cell, value) { 
			cell.innerHTML = "<a onclick=\"if (confirm('Are you sure you want to delete this account? ')) deleteAccount(editableGrid.getRow(" + cell.rowIndex + ").childNodes[0].innerHTML);editableGrid.remove(" + cell.rowIndex + ");\" style=\"cursor:pointer\">" +
							 "<img src=\"images\\delete.png\" border=\"0\" alt=\"delete\" title=\"delete\"/></a>";
		}})); 
		editableGrid.modelChanged = function(rowIdx, colIdx, oldValue, newValue, row) { 
			if(isNaN(newValue) || newValue == '') { updateAccountItem(this.getValueAt(rowIdx, this.getColumnIndex("accountID")), this.getColumnName(colIdx), "'" + newValue + "'"); }
			else { updateAccountItem(this.getValueAt(rowIdx, this.getColumnIndex("accountID")), this.getColumnName(colIdx), newValue); }
		}
		editableGrid.renderGrid("accounts_wrapper", "editablegrid");
	}).error(function(jqXHR, textStatus, errorThrown){ alert(errorThrown); });
});

function deleteAccount(id){
	$.ajax({
		type: "POST",
		url: "delete_account.php",
		data: { accountID: id },
		success: function(data, textStatus, jqXHR) { 
			if(data == "1"){
				alert("Account Removed");
			} else { alert("Error: " + data); }
		}
	});
}

function updateAccountItem(id, accountItem, newValue){
	$.ajax({
		type: "POST",
		url: "change_account_item.php",
		data: { accountID: id,
				accountItem: accountItem,
				newValue: newValue },
		success: function(data, textStatus, jqXHR){
			if(data == "1"){
				_$("message").innerHTML = "<p class='ok'>" + accountItem + " changed to " + newValue + "</p>"; 
				//alert("Item updated");	
			} else { alert("Error: " + data); }
		}
	});
}
