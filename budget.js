// JavaScript Document

var editableGrid = null;

Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator, currencySymbol) {
    // check the args and supply defaults:
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces;
    decSeparator = decSeparator == undefined ? "." : decSeparator;
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator;
    currencySymbol = currencySymbol == undefined ? "$" : currencySymbol;

    var n = this,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;

    return sign + currencySymbol + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

$(document).ready(function() {
		$.getJSON("getAccountsMinimal.php", function(data){
			$.each(data, function(i, v){
				$("#account_select").append($("<option/>", {
					value: v.accountID,
					text: v.accountName
				}));
			});
		});
		
		$("#account_select").on("change", function() {
			$.post("getBudget.php", { accountID: $(this).val() }, function(json) {
				editableGrid = new EditableGrid("Budget");
				editableGrid.ignoreLastRow = true;
				editableGrid.loadJSONFromString(json);
				editableGrid.dateFormat = "US";
				this.editableGrid = editableGrid;
				reloadRenderers();
	
				editableGrid.modelChanged = function(rowIdx, colIdx, oldValue, newValue, row) { 
					switch(this.getColumnName(colIdx)){
						case "quantity":
							var newQuan = parseInt(newValue);
							var unitPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("unitPrice")));
							var purpose = this.getValueAt(rowIdx, this.getColumnIndex("purpose")).toLowerCase();
							if(purpose == "credit" || purpose == "budget" || purpose == "3" || purpose == "0"){ 
								this.setValueAt(rowIdx, this.getColumnIndex("totalPrice"), newQuan * unitPrice);
								this.getCell(rowIdx, this.getColumnIndex("totalPrice")).style.color = "Black";
								var totalPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("totalPrice")));
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "quantity", newQuan);
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "totalPrice", totalPrice);
								recalculateGrandTotal();
							} else {
								this.setValueAt(rowIdx, this.getColumnIndex("totalPrice"), newQuan * unitPrice * -1);
								this.getCell(rowIdx, this.getColumnIndex("totalPrice")).style.color = "Red";
								var totalPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("totalPrice")));
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "quantity", newQuan);
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "totalPrice", totalPrice);
								recalculateGrandTotal();
							}
							break;
						case "unitPrice":
							var newUnitPrice = parseFloat(newValue);
							var quantity = parseInt(this.getValueAt(rowIdx, this.getColumnIndex("quantity")));
							var purpose = this.getValueAt(rowIdx, this.getColumnIndex("purpose")).toLowerCase();
							if(purpose == "credit" || purpose == "budget" || purpose == "3" || purpose == "0"){
								this.setValueAt(rowIdx, this.getColumnIndex("totalPrice"), newUnitPrice * quantity);
								this.getCell(rowIdx, this.getColumnIndex("totalPrice")).style.color = "Black";
								var totalPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("totalPrice")));
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "unitPrice", newUnitPrice);
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "totalPrice", totalPrice);
								recalculateGrandTotal();
							} else {
								this.setValueAt(rowIdx, this.getColumnIndex("totalPrice"), newUnitPrice * quantity * -1);
								this.getCell(rowIdx, this.getColumnIndex("totalPrice")).style.color = "Red";
								var totalPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("totalPrice")));
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "unitPrice", newUnitPrice);
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "totalPrice", totalPrice);
								recalculateGrandTotal();
							}
							break;
						case "purpose":
							var unitPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("unitPrice")));
							var quantity = parseInt(this.getValueAt(rowIdx, this.getColumnIndex("quantity")));
							var purpose = newValue;
							if(purpose == "credit" || purpose == "budget" || purpose == "3" || purpose == "0"){
								this.setValueAt(rowIdx, this.getColumnIndex("totalPrice"), unitPrice * quantity);
								this.getCell(rowIdx, this.getColumnIndex("totalPrice")).style.color = "Black";
								var totalPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("totalPrice")));
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "purpose", purpose);
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "totalPrice", totalPrice);
								recalculateGrandTotal();
							} else {
								//need to figure out credit
								this.setValueAt(rowIdx, this.getColumnIndex("totalPrice"), unitPrice * quantity * -1);
								this.getCell(rowIdx, this.getColumnIndex("totalPrice")).style.color = "Black";
								var totalPrice = parseFloat(this.getValueAt(rowIdx, this.getColumnIndex("totalPrice")));
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "purpose", purpose);
								updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), "totalPrice", totalPrice);
								recalculateGrandTotal();
							}
							break;
						case "invoice":
							break;
						default: 
							updateBudgetItem(this.getValueAt(rowIdx, this.getColumnIndex("budgetItemID")), this.getColumnName(colIdx), "'" + newValue + "'");
							this.sort("description", true, false);
							break;
					}
					
					
				}
				
				editableGrid.renderGrid("budget_wrapper", "editablegrid");
				editableGrid.sort("description", false, false);
																
				var rowCount = editableGrid.getRowCount();
				if(rowCount > 0){
					var grandTotal = 0;
					for(var i = 0; i < rowCount; i++){
						var rowValue = editableGrid.getValueAt(i, editableGrid.getColumnIndex("totalPrice"));
						grandTotal += rowValue;
					}
					editableGrid.append("grandTotal", {"totalPrice" : grandTotal}, {}, true);
				}				
			});
		});
	}
);

function reloadBudget(userID){
	$.post("getBudget.php", { accountID: userID },
		function(json){
			reloadBudgetPostback(json);
		}
	);
}

function reloadBudgetPostback(json){
	this.editableGrid.loadJSONFromString(json);
	this.editableGrid.refreshGrid();
	reloadRenderers();
	recalculateGrandTotal();
	this.editableGrid.sort("description", false, false);
}

function reloadRenderers(){
	this.editableGrid.setCellRenderer("action", new CellRenderer({ render: function(cell, value) { 
				cell.innerHTML = "<a onclick=\"if (confirm('Are you sure you want to delete this budget line item? ')) \
					deleteBudgetItem(editableGrid.getRow(" + cell.rowIndex + ").childNodes[0].innerHTML); \
					editableGrid.remove(" + cell.rowIndex + "); \
					\" style=\"cursor:pointer\">" +
					"<img src=\"images\\delete.png\" border=\"0\" alt=\"delete\" title=\"delete\"/></a>";
			}
		})
	); 
				
	/*this.editableGrid.setCellRenderer("totalPrice", new NumberCellRenderer({ render: function(c, e) {
				var d = this.column || {};
				var a = typeof e == "number" && isNaN(e);
				var b = a ? (d.nansymbol || "") : e;
				if(typeof b == "number") {
					if(d.precision !== null) {
						b = number_format(b, d.precision, d.decimal_point, d.thousands_separator);
					}
					if(d.unit!==null) { 
						if(d.unit_before_number) {
							b = d.unit + " " + b;
						} else { b = b + " " + d.unit; }
					}
				}
				c.innerHTML = b;
				c.style.fontWeight = a ? "normal" : "";
				if(e < 0) { c.style.color = "DarkRed"; }
				else { c.style.color = "Black"; }
			}
		})
	);*/
}

function recalculateGrandTotal(){
	var editableGrid = this.editableGrid;
	
	if(editableGrid.getRowIndex("grandTotal") < 0){
		var rowCount = editableGrid.getRowCount();
		var grandTotal = 0;
		for(var i = 0; i < rowCount; i++){
			var rowValue = editableGrid.getValueAt(i, editableGrid.getColumnIndex("totalPrice"));
			grandTotal += rowValue;
		}
		editableGrid.append("grandTotal", {"totalPrice" : grandTotal}, {}, true); 
	}
	else { 
		var rowCount = editableGrid.getRowCount() - 1;
		var grandTotal = 0;
		for(var i = 0; i < rowCount; i++){
			var rowValue = editableGrid.getValueAt(i, editableGrid.getColumnIndex("totalPrice"));
			grandTotal += rowValue;
		}
		editableGrid.setValueAt(editableGrid.getRowIndex("grandTotal"), editableGrid.getColumnIndex("totalPrice"), grandTotal); 
	}
	
	if(grandTotal < 0) { editableGrid.getCell(editableGrid.getRowIndex("grandTotal"), editableGrid.getColumnIndex("totalPrice")).style.color = "DarkRed"; }
}

function deleteBudgetItem(id){
	$.ajax({
		type: "POST",
		url: "delete_budget_item.php",
		data: { budgetItemID : id },
		success: function(data, textStatus, jqXHR){
			if(data == "1"){
				alert("Item Removed");
			} else { alert("Error: " + data); }
		}
	});
}

function updateBudgetItem(id, budgetItem, newValue){
	$.ajax({
		type: "POST",
		url: "change_budget_item.php",
		data: { budgetItemID: id,
				budgetItem: budgetItem,
				newValue: newValue },
		success: function(data, textStatus, jqXHR){
			if(data == "1"){
				_$("message").innerHTML = "<p class='ok'>" + budgetItem + " changed to " + newValue + "</p>"; 
				//alert("Item updated");	
			} else { alert("Error: " + data); }
		}
	});
}

function getUserDetails(id, isInvoice){
	$.ajax({
		type: "POST",
		url: "get_account_detail.php",
		data: { accountID: id },
		success: function(data, textStatus, jqXHR){
			createInvoice(data, isInvoice);
		}
	});
}

function startInvoice(isInvoice){
	var currentAccount = $("#account_select").val();
	getUserDetails(currentAccount, isInvoice);
}

function getExportItems(isInvoice){
	var eg = this.editableGrid;
	var consult = [];
	var credits = [];
	var debits = [];
	var budget = [];
	
	if(!eg){
		return false;
	} else {
		var count = eg.getRowCount();
		if(count < 0){
			return false;
		}
		eg.sort("date", false, false);
		for(i = 0; i < count; i++){
			var row = eg.getRowValues(i);
			if(row.invoice || !isInvoice){
				switch(row.purpose){
					case "0":
						//credit
						credits.push(
							{
								date: row.date,
								description: row.description,
								quantity: row.quantity,
								unitPrice: row.unitPrice,
								totalPrice: row.totalPrice
							}
						);
						break;
					case "1":
						//debit
						debits.push(
							{
								date: row.date,
								description: row.description,
								quantity: row.quantity,
								unitPrice: row.unitPrice,
								totalPrice: row.totalPrice
							}
						);
						break;
					case "2":
						//time logged
						consult.push(
							{
								date: row.date,
								description: row.description,
								quantity: row.quantity,
								unitPrice: row.unitPrice,
								totalPrice: row.totalPrice
							}
						);
						break;
					case "3":
						budget.push(
							{
								date: row.date,
								description: row.description,
								quantity: row.quantity,
								unitPrice: row.unitPrice,
								totalPrice: row.totalPrice
							}
						);
						break;
					default: break;
				}
			}
		}
		return [consult, debits, credits, budget];
	}
}

function createInvoice(userString, isInvoice){
	var items = getExportItems(isInvoice);
	
	if(items){
		var doc = new jsPDF("p", "mm", "letter");
		var dataString = "data:image/jpeg;base64," + img;
		var user = $.parseJSON(userString);
		
		doc.addImage(dataString, 'jpeg', 55, 5, 100, 50);
		
		doc.setFontSize(30);
		doc.setFontType("bold");
		if(isInvoice){ doc.text(163, 21, "INVOICE"); }
		else { doc.text(163, 21, "BUDGET"); }
	
		doc.setFontSize(10);
		doc.setFontType("normal");
		doc.text(35, 50, "14297 Matt Street");
		doc.setLineWidth("0.5");
		doc.line(68, 47, 68, 51);
		doc.text(73, 50, "Carmel, IN 46033");
		doc.line(105, 47, 105, 51);
		doc.text(110, 50, "(317) 714-8967");
		doc.line(139, 47, 139, 51);
		doc.text(144, 50, "allboyz4@indy.rr.com");
		
		doc.setFontType("italic");
		doc.text(35, 62, "Client Information");
		doc.setFontType("normal");
		doc.text(45, 67, user.firstName + " " + user.lastName);
		doc.text(45, 71, user.streetNumber + " " + user.streetName);
		doc.text(45, 75, user.city + ", " + user.state + " " + user.zipCode);
		doc.text(45, 79, user.homePhone);
		doc.text(45, 83, user.email);
		
		doc.setFontType("bold");
		doc.text(20, 95, "Date");
		doc.text(45, 95, "Description");
		doc.text(135, 95, "Quantity");
		doc.text(155, 95, "Unit Price");
		doc.text(175, 95, "Total Price");
		doc.line(20, 97, 195, 97);
		doc.setFontType("normal");
		
		var pagePos = 103;
		var inc = 5;
		var grandTotal = 0;
		
		for(i = 0; i < items.length; i++){
			if(pagePos >= 280){
				doc.addPage();
				pagePos = 20;
			}
			if(items[i].length > 0){
				switch(i){
					case 0:
						doc.setFontType("italic");
						doc.text(20, pagePos, "Consultation");
						doc.setFontType("normal");
						pagePos += inc;
						break;
					case 1:
						doc.setFontType("italic");
						doc.text(20, pagePos, "Expenses");
						doc.setFontType("normal");
						pagePos += inc;
						break;
					case 2:
						doc.setFontType("italic");
						doc.text(20, pagePos, "Returns/Credits");
						doc.setFontType("normal");
						pagePos += inc;
						break;
					case 3:
						doc.setFontType("italic");
						doc.text(20, pagePos, "Budget Expenses");
						doc.setFontType("normal");
						pagePos += inc;
					default: break;
				}
				for(j = 0; j < items[i].length; j++){
					var row = items[i][j];
					doc.text(20, pagePos, row.date);
					doc.text(45, pagePos, row.description);
					doc.text(135, pagePos, row.quantity + '');
					doc.text(155, pagePos, row.unitPrice.formatMoney());
					doc.text(175, pagePos, row.totalPrice.formatMoney());
					pagePos += inc;
					grandTotal += row.totalPrice;
				}
			}
		}
		
		doc.setFontType("bold");
		doc.text(135, pagePos, "Grand Total");
		doc.setFontType("normal");
		doc.text(175, pagePos, grandTotal.formatMoney());
		
		doc.save(userString.accountName + ".pdf");
	} else { alert("No invoiceable items found"); }
}
