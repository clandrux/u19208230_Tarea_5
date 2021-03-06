<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "a_purchasesinfo.php" ?>
<?php include_once "a_suppliersinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "a_purchases_detailgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$a_purchases_add = NULL; // Initialize page object first

class ca_purchases_add extends ca_purchases {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B36B93AF-B58F-461B-B767-5F08C12493E9}";

	// Table name
	var $TableName = 'a_purchases';

	// Page object name
	var $PageObjName = 'a_purchases_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {

		// $hidden = TRUE;
		$hidden = MS_USE_JAVASCRIPT_MESSAGE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display

			// if (!$hidden)
			//	 $sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			// $html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			// Begin of modification Auto Hide Message, by Masino Sinaga, January 24, 2013

			if (@MS_AUTO_HIDE_SUCCESS_MESSAGE) {

				//$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
				$html .= "<p class=\"alert alert-success msSuccessMessage\" id=\"ewSuccessMessage\">" . $sSuccessMessage . "</p>";
			} else {
				if (!$hidden)
					$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
				$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			}

			// End of modification Auto Hide Message, by Masino Sinaga, January 24, 2013
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}

		// echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
		if (@MS_AUTO_HIDE_SUCCESS_MESSAGE || MS_USE_JAVASCRIPT_MESSAGE==0) {
			echo $html;
		} else {
			if (MS_USE_ALERTIFY_FOR_MESSAGE_DIALOG) {
				if ($html <> "") {
					$html = str_replace("'", "\'", $html);
					echo "<script type='text/javascript'>alertify.alert('".$html."', function (ok) { }).set('title', ewLanguage.Phrase('AlertifyAlert'));</script>";
				}
			} else {
				echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
			}
		}
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (a_purchases)
		if (!isset($GLOBALS["a_purchases"]) || get_class($GLOBALS["a_purchases"]) == "ca_purchases") {
			$GLOBALS["a_purchases"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["a_purchases"];
		}

		// Table object (a_suppliers)
		if (!isset($GLOBALS['a_suppliers'])) $GLOBALS['a_suppliers'] = new ca_suppliers();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'a_purchases');

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (users)
		if (!isset($UserTable)) {
			$UserTable = new cusers();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm, $UserTableConn;
		if (!isset($_SESSION['table_a_purchases_views'])) { 
			$_SESSION['table_a_purchases_views'] = 0;
		}
		$_SESSION['table_a_purchases_views'] = $_SESSION['table_a_purchases_views']+1;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("a_purchaseslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Begin of modification Auto Logout After Idle for the Certain Time, by Masino Sinaga, May 5, 2012
		if (IsLoggedIn() && !IsSysAdmin()) {

			// Begin of modification by Masino Sinaga, May 25, 2012 in order to not autologout after clear another user's session ID whenever back to another page.           
			$UserProfile->LoadProfileFromDatabase(CurrentUserName());

			// End of modification by Masino Sinaga, May 25, 2012 in order to not autologout after clear another user's session ID whenever back to another page.
			// Begin of modification Save Last Users' Visitted Page, by Masino Sinaga, May 25, 2012

			$lastpage = ew_CurrentPage();
			if ($lastpage!='logout.php' && $lastpage!='index.php') {
				$lasturl = ew_CurrentUrl();
				$sFilterUserID = str_replace("%u", ew_AdjustSql(CurrentUserName(), EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);
				ew_Execute("UPDATE ".EW_USER_TABLE." SET Current_URL = '".$lasturl."' WHERE ".$sFilterUserID."", $UserTableConn);
			}

			// End of modification Save Last Users' Visitted Page, by Masino Sinaga, May 25, 2012
			$LastAccessDateTime = strval(@$UserProfile->Profile[EW_USER_PROFILE_LAST_ACCESSED_DATE_TIME]);
			$nDiff = intval(ew_DateDiff($LastAccessDateTime, ew_StdCurrentDateTime(), "s"));
			$nCons = intval(MS_AUTO_LOGOUT_AFTER_IDLE_IN_MINUTES) * 60;
			if ($nDiff > $nCons) {

				//header("Location: logout.php?expired=1");
			}
		}

		// End of modification Auto Logout After Idle for the Certain Time, by Masino Sinaga, May 5, 2012
		// Update last accessed time

		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {

			// Do nothing since it's a valid user! SaveProfileToDatabase has been handled from IsValidUser method of UserProfile object.
		} else {

			// Begin of modification How to Overcome "User X already logged in" Issue, by Masino Sinaga, July 22, 2014
			// echo $Language->Phrase("UserProfileCorrupted");

			header("Location: logout.php");

			// End of modification How to Overcome "User X already logged in" Issue, by Masino Sinaga, July 22, 2014
		}
		if (@MS_USE_CONSTANTS_IN_CONFIG_FILE == FALSE) {

			// Call this new function from userfn*.php file
			My_Global_Check();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

// Begin of modification Disable/Enable Registration Page, by Masino Sinaga, May 14, 2012
// End of modification Disable/Enable Registration Page, by Masino Sinaga, May 14, 2012
		// Page Load event

		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}
		if (ALWAYS_COMPARE_ROOT_URL == TRUE) {
			if ($_SESSION['php_stock_Root_URL'] <> Get_Root_URL()) {
				header("Location: " . $_SESSION['php_stock_Root_URL']);
			}
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 'a_purchases_detail'
			if (@$_POST["grid"] == "fa_purchases_detailgrid") {
				if (!isset($GLOBALS["a_purchases_detail_grid"])) $GLOBALS["a_purchases_detail_grid"] = new ca_purchases_detail_grid;
				$GLOBALS["a_purchases_detail_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $a_purchases;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($a_purchases);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values

			// End of modification Permission Access for Export To Feature, by Masino Sinaga, May 5, 2012
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Purchase_ID"] != "") {
				$this->Purchase_ID->setQueryStringValue($_GET["Purchase_ID"]);
				$this->setKey("Purchase_ID", $this->Purchase_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("Purchase_ID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("a_purchaseslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful

					// Begin of modification Disable Add/Edit Success Message Box, by Masino Sinaga, August 1, 2012
					if (MS_SHOW_ADD_SUCCESS_MESSAGE==TRUE) {
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					}

					// End of modification Disable Add/Edit Success Message Box, by Masino Sinaga, August 1, 2012
					$sReturnUrl = "a_purchaseslist.php";
					if (ew_GetPageName($sReturnUrl) == "a_purchaseslist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "a_purchasesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Purchase_Number->CurrentValue = NULL;
		$this->Purchase_Number->OldValue = $this->Purchase_Number->CurrentValue;
		$this->Purchase_Date->CurrentValue = ew_CurrentDateTime();
		$this->Supplier_ID->CurrentValue = NULL;
		$this->Supplier_ID->OldValue = $this->Supplier_ID->CurrentValue;
		$this->Notes->CurrentValue = NULL;
		$this->Notes->OldValue = $this->Notes->CurrentValue;
		$this->Total_Amount->CurrentValue = 0;
		$this->Total_Payment->CurrentValue = 0;
		$this->Total_Balance->CurrentValue = 0;
		$this->Date_Added->CurrentValue = ew_CurrentDateTime();
		$this->Added_By->CurrentValue = CurrentUserName();
		$this->Date_Updated->CurrentValue = NULL;
		$this->Date_Updated->OldValue = $this->Date_Updated->CurrentValue;
		$this->Updated_By->CurrentValue = NULL;
		$this->Updated_By->OldValue = $this->Updated_By->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Purchase_Number->FldIsDetailKey) {
			$this->Purchase_Number->setFormValue($objForm->GetValue("x_Purchase_Number"));
		}
		if (!$this->Purchase_Date->FldIsDetailKey) {
			$this->Purchase_Date->setFormValue($objForm->GetValue("x_Purchase_Date"));
			$this->Purchase_Date->CurrentValue = ew_UnFormatDateTime($this->Purchase_Date->CurrentValue, 9);
		}
		if (!$this->Supplier_ID->FldIsDetailKey) {
			$this->Supplier_ID->setFormValue($objForm->GetValue("x_Supplier_ID"));
		}
		if (!$this->Notes->FldIsDetailKey) {
			$this->Notes->setFormValue($objForm->GetValue("x_Notes"));
		}
		if (!$this->Total_Amount->FldIsDetailKey) {
			$this->Total_Amount->setFormValue($objForm->GetValue("x_Total_Amount"));
		}
		if (!$this->Total_Payment->FldIsDetailKey) {
			$this->Total_Payment->setFormValue($objForm->GetValue("x_Total_Payment"));
		}
		if (!$this->Total_Balance->FldIsDetailKey) {
			$this->Total_Balance->setFormValue($objForm->GetValue("x_Total_Balance"));
		}
		if (!$this->Date_Added->FldIsDetailKey) {
			$this->Date_Added->setFormValue($objForm->GetValue("x_Date_Added"));
			$this->Date_Added->CurrentValue = ew_UnFormatDateTime($this->Date_Added->CurrentValue, 0);
		}
		if (!$this->Added_By->FldIsDetailKey) {
			$this->Added_By->setFormValue($objForm->GetValue("x_Added_By"));
		}
		if (!$this->Date_Updated->FldIsDetailKey) {
			$this->Date_Updated->setFormValue($objForm->GetValue("x_Date_Updated"));
			$this->Date_Updated->CurrentValue = ew_UnFormatDateTime($this->Date_Updated->CurrentValue, 0);
		}
		if (!$this->Updated_By->FldIsDetailKey) {
			$this->Updated_By->setFormValue($objForm->GetValue("x_Updated_By"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Purchase_Number->CurrentValue = $this->Purchase_Number->FormValue;
		$this->Purchase_Date->CurrentValue = $this->Purchase_Date->FormValue;
		$this->Purchase_Date->CurrentValue = ew_UnFormatDateTime($this->Purchase_Date->CurrentValue, 9);
		$this->Supplier_ID->CurrentValue = $this->Supplier_ID->FormValue;
		$this->Notes->CurrentValue = $this->Notes->FormValue;
		$this->Total_Amount->CurrentValue = $this->Total_Amount->FormValue;
		$this->Total_Payment->CurrentValue = $this->Total_Payment->FormValue;
		$this->Total_Balance->CurrentValue = $this->Total_Balance->FormValue;
		$this->Date_Added->CurrentValue = $this->Date_Added->FormValue;
		$this->Date_Added->CurrentValue = ew_UnFormatDateTime($this->Date_Added->CurrentValue, 0);
		$this->Added_By->CurrentValue = $this->Added_By->FormValue;
		$this->Date_Updated->CurrentValue = $this->Date_Updated->FormValue;
		$this->Date_Updated->CurrentValue = ew_UnFormatDateTime($this->Date_Updated->CurrentValue, 0);
		$this->Updated_By->CurrentValue = $this->Updated_By->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->Purchase_ID->setDbValue($rs->fields('Purchase_ID'));
		$this->Purchase_Number->setDbValue($rs->fields('Purchase_Number'));
		$this->Purchase_Date->setDbValue($rs->fields('Purchase_Date'));
		$this->Supplier_ID->setDbValue($rs->fields('Supplier_ID'));
		$this->Notes->setDbValue($rs->fields('Notes'));
		$this->Total_Amount->setDbValue($rs->fields('Total_Amount'));
		$this->Total_Payment->setDbValue($rs->fields('Total_Payment'));
		$this->Total_Balance->setDbValue($rs->fields('Total_Balance'));
		$this->Date_Added->setDbValue($rs->fields('Date_Added'));
		$this->Added_By->setDbValue($rs->fields('Added_By'));
		$this->Date_Updated->setDbValue($rs->fields('Date_Updated'));
		$this->Updated_By->setDbValue($rs->fields('Updated_By'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Purchase_ID->DbValue = $row['Purchase_ID'];
		$this->Purchase_Number->DbValue = $row['Purchase_Number'];
		$this->Purchase_Date->DbValue = $row['Purchase_Date'];
		$this->Supplier_ID->DbValue = $row['Supplier_ID'];
		$this->Notes->DbValue = $row['Notes'];
		$this->Total_Amount->DbValue = $row['Total_Amount'];
		$this->Total_Payment->DbValue = $row['Total_Payment'];
		$this->Total_Balance->DbValue = $row['Total_Balance'];
		$this->Date_Added->DbValue = $row['Date_Added'];
		$this->Added_By->DbValue = $row['Added_By'];
		$this->Date_Updated->DbValue = $row['Date_Updated'];
		$this->Updated_By->DbValue = $row['Updated_By'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Purchase_ID")) <> "")
			$this->Purchase_ID->CurrentValue = $this->getKey("Purchase_ID"); // Purchase_ID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->Total_Amount->FormValue == $this->Total_Amount->CurrentValue && is_numeric(ew_StrToFloat($this->Total_Amount->CurrentValue)))
			$this->Total_Amount->CurrentValue = ew_StrToFloat($this->Total_Amount->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Total_Payment->FormValue == $this->Total_Payment->CurrentValue && is_numeric(ew_StrToFloat($this->Total_Payment->CurrentValue)))
			$this->Total_Payment->CurrentValue = ew_StrToFloat($this->Total_Payment->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Total_Balance->FormValue == $this->Total_Balance->CurrentValue && is_numeric(ew_StrToFloat($this->Total_Balance->CurrentValue)))
			$this->Total_Balance->CurrentValue = ew_StrToFloat($this->Total_Balance->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Purchase_ID
		// Purchase_Number
		// Purchase_Date
		// Supplier_ID
		// Notes
		// Total_Amount
		// Total_Payment
		// Total_Balance
		// Date_Added
		// Added_By
		// Date_Updated
		// Updated_By

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Purchase_Number
		$this->Purchase_Number->ViewValue = $this->Purchase_Number->CurrentValue;
		$this->Purchase_Number->ViewCustomAttributes = "";

		// Purchase_Date
		$this->Purchase_Date->ViewValue = $this->Purchase_Date->CurrentValue;
		$this->Purchase_Date->ViewValue = ew_FormatDateTime($this->Purchase_Date->ViewValue, 9);
		$this->Purchase_Date->ViewCustomAttributes = "";

		// Supplier_ID
		if (strval($this->Supplier_ID->CurrentValue) <> "") {
			$sFilterWrk = "`Supplier_Number`" . ew_SearchString("=", $this->Supplier_ID->CurrentValue, EW_DATATYPE_STRING, "");
		switch (@$gsLanguage) {
			case "id":
				$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
				$sWhereWrk = "";
				break;
		}
		$lookuptblfilter = (isset($_GET["Supplier_Number"])) ? "Supplier_Number = '".$_GET["Supplier_Number"]."'" : "";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Supplier_ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Supplier_ID->ViewValue = $this->Supplier_ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Supplier_ID->ViewValue = $this->Supplier_ID->CurrentValue;
			}
		} else {
			$this->Supplier_ID->ViewValue = NULL;
		}
		$this->Supplier_ID->ViewCustomAttributes = "";

		// Notes
		$this->Notes->ViewValue = $this->Notes->CurrentValue;
		$this->Notes->ViewCustomAttributes = "";

		// Total_Amount
		$this->Total_Amount->ViewValue = $this->Total_Amount->CurrentValue;
		$this->Total_Amount->ViewValue = ew_FormatCurrency($this->Total_Amount->ViewValue, 0, -2, -2, -2);
		$this->Total_Amount->CellCssStyle .= "text-align: right;";
		$this->Total_Amount->ViewCustomAttributes = "";

		// Total_Payment
		$this->Total_Payment->ViewValue = $this->Total_Payment->CurrentValue;
		$this->Total_Payment->ViewValue = ew_FormatCurrency($this->Total_Payment->ViewValue, 0, -2, -2, -2);
		$this->Total_Payment->CellCssStyle .= "text-align: right;";
		$this->Total_Payment->ViewCustomAttributes = "";

		// Total_Balance
		$this->Total_Balance->ViewValue = $this->Total_Balance->CurrentValue;
		$this->Total_Balance->ViewValue = ew_FormatCurrency($this->Total_Balance->ViewValue, 0, -2, -2, -2);
		$this->Total_Balance->CellCssStyle .= "text-align: right;";
		$this->Total_Balance->ViewCustomAttributes = "";

		// Date_Added
		$this->Date_Added->ViewValue = $this->Date_Added->CurrentValue;
		$this->Date_Added->ViewCustomAttributes = "";

		// Added_By
		$this->Added_By->ViewValue = $this->Added_By->CurrentValue;
		$this->Added_By->ViewCustomAttributes = "";

		// Date_Updated
		$this->Date_Updated->ViewValue = $this->Date_Updated->CurrentValue;
		$this->Date_Updated->ViewCustomAttributes = "";

		// Updated_By
		$this->Updated_By->ViewValue = $this->Updated_By->CurrentValue;
		$this->Updated_By->ViewCustomAttributes = "";

			// Purchase_Number
			$this->Purchase_Number->LinkCustomAttributes = "";
			$this->Purchase_Number->HrefValue = "";
			$this->Purchase_Number->TooltipValue = "";

			// Purchase_Date
			$this->Purchase_Date->LinkCustomAttributes = "";
			$this->Purchase_Date->HrefValue = "";
			$this->Purchase_Date->TooltipValue = "";

			// Supplier_ID
			$this->Supplier_ID->LinkCustomAttributes = "";
			$this->Supplier_ID->HrefValue = "";
			$this->Supplier_ID->TooltipValue = "";

			// Notes
			$this->Notes->LinkCustomAttributes = "";
			$this->Notes->HrefValue = "";
			$this->Notes->TooltipValue = "";

			// Total_Amount
			$this->Total_Amount->LinkCustomAttributes = "";
			$this->Total_Amount->HrefValue = "";
			$this->Total_Amount->TooltipValue = "";

			// Total_Payment
			$this->Total_Payment->LinkCustomAttributes = "";
			$this->Total_Payment->HrefValue = "";
			$this->Total_Payment->TooltipValue = "";

			// Total_Balance
			$this->Total_Balance->LinkCustomAttributes = "";
			$this->Total_Balance->HrefValue = "";
			$this->Total_Balance->TooltipValue = "";

			// Date_Added
			$this->Date_Added->LinkCustomAttributes = "";
			$this->Date_Added->HrefValue = "";
			$this->Date_Added->TooltipValue = "";

			// Added_By
			$this->Added_By->LinkCustomAttributes = "";
			$this->Added_By->HrefValue = "";
			$this->Added_By->TooltipValue = "";

			// Date_Updated
			$this->Date_Updated->LinkCustomAttributes = "";
			$this->Date_Updated->HrefValue = "";
			$this->Date_Updated->TooltipValue = "";

			// Updated_By
			$this->Updated_By->LinkCustomAttributes = "";
			$this->Updated_By->HrefValue = "";
			$this->Updated_By->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Purchase_Number
			$this->Purchase_Number->EditAttrs["class"] = "form-control";
			$this->Purchase_Number->EditCustomAttributes = "";
			$this->Purchase_Number->EditValue = ew_HtmlEncode($this->Purchase_Number->CurrentValue);
			$this->Purchase_Number->PlaceHolder = ew_RemoveHtml($this->Purchase_Number->FldCaption());

			// Purchase_Date
			$this->Purchase_Date->EditAttrs["class"] = "form-control";
			$this->Purchase_Date->EditCustomAttributes = "";
			$this->Purchase_Date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Purchase_Date->CurrentValue, 9));
			$this->Purchase_Date->PlaceHolder = ew_RemoveHtml($this->Purchase_Date->FldCaption());

			// Supplier_ID
			$this->Supplier_ID->EditAttrs["class"] = "form-control";
			$this->Supplier_ID->EditCustomAttributes = "";
			if ($this->Supplier_ID->getSessionValue() <> "") {
				$this->Supplier_ID->CurrentValue = $this->Supplier_ID->getSessionValue();
			if (strval($this->Supplier_ID->CurrentValue) <> "") {
				$sFilterWrk = "`Supplier_Number`" . ew_SearchString("=", $this->Supplier_ID->CurrentValue, EW_DATATYPE_STRING, "");
			switch (@$gsLanguage) {
				case "id":
					$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = (isset($_GET["Supplier_Number"])) ? "Supplier_Number = '".$_GET["Supplier_Number"]."'" : "";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Supplier_ID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->Supplier_ID->ViewValue = $this->Supplier_ID->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Supplier_ID->ViewValue = $this->Supplier_ID->CurrentValue;
				}
			} else {
				$this->Supplier_ID->ViewValue = NULL;
			}
			$this->Supplier_ID->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->Supplier_ID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Supplier_Number`" . ew_SearchString("=", $this->Supplier_ID->CurrentValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "id":
					$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `a_suppliers`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `a_suppliers`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = (isset($_GET["Supplier_Number"])) ? "Supplier_Number = '".$_GET["Supplier_Number"]."'" : "";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Supplier_ID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Supplier_ID->EditValue = $arwrk;
			}

			// Notes
			$this->Notes->EditAttrs["class"] = "form-control";
			$this->Notes->EditCustomAttributes = "";
			$this->Notes->EditValue = ew_HtmlEncode($this->Notes->CurrentValue);
			$this->Notes->PlaceHolder = ew_RemoveHtml($this->Notes->FldCaption());

			// Total_Amount
			$this->Total_Amount->EditAttrs["class"] = "form-control";
			$this->Total_Amount->EditCustomAttributes = "";
			$this->Total_Amount->EditValue = ew_HtmlEncode($this->Total_Amount->CurrentValue);
			$this->Total_Amount->PlaceHolder = ew_RemoveHtml($this->Total_Amount->FldCaption());
			if (strval($this->Total_Amount->EditValue) <> "" && is_numeric($this->Total_Amount->EditValue)) $this->Total_Amount->EditValue = ew_FormatNumber($this->Total_Amount->EditValue, -2, -2, -2, -2);

			// Total_Payment
			$this->Total_Payment->EditAttrs["class"] = "form-control";
			$this->Total_Payment->EditCustomAttributes = "";
			$this->Total_Payment->EditValue = ew_HtmlEncode($this->Total_Payment->CurrentValue);
			$this->Total_Payment->PlaceHolder = ew_RemoveHtml($this->Total_Payment->FldCaption());
			if (strval($this->Total_Payment->EditValue) <> "" && is_numeric($this->Total_Payment->EditValue)) $this->Total_Payment->EditValue = ew_FormatNumber($this->Total_Payment->EditValue, -2, -2, -2, -2);

			// Total_Balance
			$this->Total_Balance->EditAttrs["class"] = "form-control";
			$this->Total_Balance->EditCustomAttributes = "";
			$this->Total_Balance->EditValue = ew_HtmlEncode($this->Total_Balance->CurrentValue);
			$this->Total_Balance->PlaceHolder = ew_RemoveHtml($this->Total_Balance->FldCaption());
			if (strval($this->Total_Balance->EditValue) <> "" && is_numeric($this->Total_Balance->EditValue)) $this->Total_Balance->EditValue = ew_FormatNumber($this->Total_Balance->EditValue, -2, -2, -2, -2);

			// Date_Added
			$this->Date_Added->EditAttrs["class"] = "form-control";
			$this->Date_Added->EditCustomAttributes = "";
			$this->Date_Added->CurrentValue = ew_CurrentDateTime();

			// Added_By
			$this->Added_By->EditAttrs["class"] = "form-control";
			$this->Added_By->EditCustomAttributes = "";
			$this->Added_By->CurrentValue = CurrentUserName();

			// Date_Updated
			// Updated_By
			// Add refer script
			// Purchase_Number

			$this->Purchase_Number->LinkCustomAttributes = "";
			$this->Purchase_Number->HrefValue = "";

			// Purchase_Date
			$this->Purchase_Date->LinkCustomAttributes = "";
			$this->Purchase_Date->HrefValue = "";

			// Supplier_ID
			$this->Supplier_ID->LinkCustomAttributes = "";
			$this->Supplier_ID->HrefValue = "";

			// Notes
			$this->Notes->LinkCustomAttributes = "";
			$this->Notes->HrefValue = "";

			// Total_Amount
			$this->Total_Amount->LinkCustomAttributes = "";
			$this->Total_Amount->HrefValue = "";

			// Total_Payment
			$this->Total_Payment->LinkCustomAttributes = "";
			$this->Total_Payment->HrefValue = "";

			// Total_Balance
			$this->Total_Balance->LinkCustomAttributes = "";
			$this->Total_Balance->HrefValue = "";

			// Date_Added
			$this->Date_Added->LinkCustomAttributes = "";
			$this->Date_Added->HrefValue = "";

			// Added_By
			$this->Added_By->LinkCustomAttributes = "";
			$this->Added_By->HrefValue = "";

			// Date_Updated
			$this->Date_Updated->LinkCustomAttributes = "";
			$this->Date_Updated->HrefValue = "";

			// Updated_By
			$this->Updated_By->LinkCustomAttributes = "";
			$this->Updated_By->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Purchase_Number->FldIsDetailKey && !is_null($this->Purchase_Number->FormValue) && $this->Purchase_Number->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Purchase_Number->FldCaption(), $this->Purchase_Number->ReqErrMsg));
		}
		if (!$this->Purchase_Date->FldIsDetailKey && !is_null($this->Purchase_Date->FormValue) && $this->Purchase_Date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Purchase_Date->FldCaption(), $this->Purchase_Date->ReqErrMsg));
		}
		if (!ew_CheckDate($this->Purchase_Date->FormValue)) {
			ew_AddMessage($gsFormError, $this->Purchase_Date->FldErrMsg());
		}
		if (!$this->Supplier_ID->FldIsDetailKey && !is_null($this->Supplier_ID->FormValue) && $this->Supplier_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Supplier_ID->FldCaption(), $this->Supplier_ID->ReqErrMsg));
		}
		if (!$this->Total_Amount->FldIsDetailKey && !is_null($this->Total_Amount->FormValue) && $this->Total_Amount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Total_Amount->FldCaption(), $this->Total_Amount->ReqErrMsg));
		}
		if (!ew_CheckRange($this->Total_Amount->FormValue, 1, 999999999)) {
			ew_AddMessage($gsFormError, $this->Total_Amount->FldErrMsg());
		}
		if (!$this->Total_Payment->FldIsDetailKey && !is_null($this->Total_Payment->FormValue) && $this->Total_Payment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Total_Payment->FldCaption(), $this->Total_Payment->ReqErrMsg));
		}
		if (!ew_CheckRange($this->Total_Payment->FormValue, 1, 999999999)) {
			ew_AddMessage($gsFormError, $this->Total_Payment->FldErrMsg());
		}
		if (!$this->Total_Balance->FldIsDetailKey && !is_null($this->Total_Balance->FormValue) && $this->Total_Balance->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Total_Balance->FldCaption(), $this->Total_Balance->ReqErrMsg));
		}
		if (!ew_CheckRange($this->Total_Balance->FormValue, 0, 999999999)) {
			ew_AddMessage($gsFormError, $this->Total_Balance->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("a_purchases_detail", $DetailTblVar) && $GLOBALS["a_purchases_detail"]->DetailAdd) {
			if (!isset($GLOBALS["a_purchases_detail_grid"])) $GLOBALS["a_purchases_detail_grid"] = new ca_purchases_detail_grid(); // get detail page object
			$GLOBALS["a_purchases_detail_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Purchase_Number
		$this->Purchase_Number->SetDbValueDef($rsnew, $this->Purchase_Number->CurrentValue, "", FALSE);

		// Purchase_Date
		$this->Purchase_Date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Purchase_Date->CurrentValue, 9), ew_CurrentDate(), FALSE);

		// Supplier_ID
		$this->Supplier_ID->SetDbValueDef($rsnew, $this->Supplier_ID->CurrentValue, "", FALSE);

		// Notes
		$this->Notes->SetDbValueDef($rsnew, $this->Notes->CurrentValue, NULL, FALSE);

		// Total_Amount
		$this->Total_Amount->SetDbValueDef($rsnew, $this->Total_Amount->CurrentValue, NULL, strval($this->Total_Amount->CurrentValue) == "");

		// Total_Payment
		$this->Total_Payment->SetDbValueDef($rsnew, $this->Total_Payment->CurrentValue, NULL, strval($this->Total_Payment->CurrentValue) == "");

		// Total_Balance
		$this->Total_Balance->SetDbValueDef($rsnew, $this->Total_Balance->CurrentValue, NULL, strval($this->Total_Balance->CurrentValue) == "");

		// Date_Added
		$this->Date_Added->SetDbValueDef($rsnew, $this->Date_Added->CurrentValue, NULL, FALSE);

		// Added_By
		$this->Added_By->SetDbValueDef($rsnew, $this->Added_By->CurrentValue, NULL, FALSE);

		// Date_Updated
		$this->Date_Updated->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['Date_Updated'] = &$this->Date_Updated->DbValue;

		// Updated_By
		$this->Updated_By->SetDbValueDef($rsnew, CurrentUserName(), NULL);
		$rsnew['Updated_By'] = &$this->Updated_By->DbValue;

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"]; // v11.0.4
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->Purchase_ID->setDbValue($conn->Insert_ID());
				$rsnew['Purchase_ID'] = $this->Purchase_ID->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("a_purchases_detail", $DetailTblVar) && $GLOBALS["a_purchases_detail"]->DetailAdd) {
				$GLOBALS["a_purchases_detail"]->Purchase_Number->setSessionValue($this->Purchase_Number->CurrentValue); // Set master key
				if (!isset($GLOBALS["a_purchases_detail_grid"])) $GLOBALS["a_purchases_detail_grid"] = new ca_purchases_detail_grid(); // Get detail page object
				$AddRow = $GLOBALS["a_purchases_detail_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["a_purchases_detail"]->Purchase_Number->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Build export filter for selected records
	function BuildExportSelectedFilter() {
		global $Language;
		$sWrkFilter = "";
		if ($this->Export <> "") {
			$sWrkFilter = $this->GetKeyFilter();
		}
		return $sWrkFilter;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "a_suppliers") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Supplier_Number"] <> "") {
					$GLOBALS["a_suppliers"]->Supplier_Number->setQueryStringValue($_GET["fk_Supplier_Number"]);
					$this->Supplier_ID->setQueryStringValue($GLOBALS["a_suppliers"]->Supplier_Number->QueryStringValue);
					$this->Supplier_ID->setSessionValue($this->Supplier_ID->QueryStringValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "a_suppliers") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Supplier_Number"] <> "") {
					$GLOBALS["a_suppliers"]->Supplier_Number->setFormValue($_POST["fk_Supplier_Number"]);
					$this->Supplier_ID->setFormValue($GLOBALS["a_suppliers"]->Supplier_Number->FormValue);
					$this->Supplier_ID->setSessionValue($this->Supplier_ID->FormValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "a_suppliers") {
				if ($this->Supplier_ID->CurrentValue == "") $this->Supplier_ID->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("a_purchases_detail", $DetailTblVar)) {
				if (!isset($GLOBALS["a_purchases_detail_grid"]))
					$GLOBALS["a_purchases_detail_grid"] = new ca_purchases_detail_grid;
				if ($GLOBALS["a_purchases_detail_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["a_purchases_detail_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["a_purchases_detail_grid"]->CurrentMode = "add";
					$GLOBALS["a_purchases_detail_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["a_purchases_detail_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["a_purchases_detail_grid"]->setStartRecordNumber(1);
					$GLOBALS["a_purchases_detail_grid"]->Purchase_Number->FldIsDetailKey = TRUE;
					$GLOBALS["a_purchases_detail_grid"]->Purchase_Number->CurrentValue = $this->Purchase_Number->CurrentValue;
					$GLOBALS["a_purchases_detail_grid"]->Purchase_Number->setSessionValue($GLOBALS["a_purchases_detail_grid"]->Purchase_Number->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1); // v11.0.4
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("a_purchaseslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url); // v11.0.4
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

		if (isset($_GET["Supplier_Number"])) {	
			$_SESSION["Supplier_Number_Purchasing"] = $_GET["Supplier_Number"];
		} else {
			if ($this->CurrentAction == "A") {

				// after adding a new record, redirect to List Page
			} else {

				//$this->setWarningMessage("Please click on <strong>Purchase Now</strong> related to the Supplier in the List below!");
				//$url = "a_supplierslist.php";

			}
		}
	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($a_purchases_add)) $a_purchases_add = new ca_purchases_add();

// Page init
$a_purchases_add->Page_Init();

// Page main
$a_purchases_add->Page_Main();

// Begin of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
getCurrentPageTitle(ew_CurrentPage());

// End of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
// Global Page Rendering event (in userfn*.php)

Page_Rendering();

// Global auto switch table width style (in userfn*.php), by Masino Sinaga, January 7, 2015
AutoSwitchTableWidthStyle();

// Page Rendering event
$a_purchases_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fa_purchasesadd = new ew_Form("fa_purchasesadd", "add");

// Validate form
fa_purchasesadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Purchase_Number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $a_purchases->Purchase_Number->FldCaption(), $a_purchases->Purchase_Number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Purchase_Date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $a_purchases->Purchase_Date->FldCaption(), $a_purchases->Purchase_Date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Purchase_Date");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($a_purchases->Purchase_Date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Supplier_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $a_purchases->Supplier_ID->FldCaption(), $a_purchases->Supplier_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Total_Amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $a_purchases->Total_Amount->FldCaption(), $a_purchases->Total_Amount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Total_Amount");
			if (elm && !ew_CheckRange(elm.value, 1, 999999999))
				return this.OnError(elm, "<?php echo ew_JsEncode2($a_purchases->Total_Amount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Total_Payment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $a_purchases->Total_Payment->FldCaption(), $a_purchases->Total_Payment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Total_Payment");
			if (elm && !ew_CheckRange(elm.value, 1, 999999999))
				return this.OnError(elm, "<?php echo ew_JsEncode2($a_purchases->Total_Payment->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Total_Balance");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $a_purchases->Total_Balance->FldCaption(), $a_purchases->Total_Balance->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Total_Balance");
			if (elm && !ew_CheckRange(elm.value, 0, 999999999))
				return this.OnError(elm, "<?php echo ew_JsEncode2($a_purchases->Total_Balance->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fa_purchasesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fa_purchasesadd.ValidateRequired = true;
<?php } else { ?>
fa_purchasesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fa_purchasesadd.Lists["x_Supplier_ID"] = {"LinkField":"x_Supplier_Number","Ajax":true,"AutoFill":false,"DisplayFields":["x_Supplier_Name","","",""],"ParentFields":[],"ChildFields":["a_purchases_detail x_Supplier_Number"],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (MS_SHOW_PHPMAKER_BREADCRUMBLINKS) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if (MS_SHOW_MASINO_BREADCRUMBLINKS) { ?>
<?php echo MasinoBreadcrumbLinks(); ?>
<?php } ?>
<?php if (MS_LANGUAGE_SELECTOR_VISIBILITY=="belowheader") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $a_purchases_add->ShowPageHeader(); ?>
<?php
$a_purchases_add->ShowMessage();
?>
<form name="fa_purchasesadd" id="fa_purchasesadd" class="<?php echo $a_purchases_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($a_purchases_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $a_purchases_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="a_purchases">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($a_purchases->getCurrentMasterTable() == "a_suppliers") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="a_suppliers">
<input type="hidden" name="fk_Supplier_Number" value="<?php echo $a_purchases->Supplier_ID->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($a_purchases->Purchase_Number->Visible) { // Purchase_Number ?>
	<div id="r_Purchase_Number" class="form-group">
		<label id="elh_a_purchases_Purchase_Number" for="x_Purchase_Number" class="col-sm-4 control-label ewLabel"><?php echo $a_purchases->Purchase_Number->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $a_purchases->Purchase_Number->CellAttributes() ?>>
<span id="el_a_purchases_Purchase_Number">
<input type="text" data-table="a_purchases" data-field="x_Purchase_Number" name="x_Purchase_Number" id="x_Purchase_Number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($a_purchases->Purchase_Number->getPlaceHolder()) ?>" value="<?php echo $a_purchases->Purchase_Number->EditValue ?>"<?php echo $a_purchases->Purchase_Number->EditAttributes() ?>>
</span>
<?php echo $a_purchases->Purchase_Number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_purchases->Purchase_Date->Visible) { // Purchase_Date ?>
	<div id="r_Purchase_Date" class="form-group">
		<label id="elh_a_purchases_Purchase_Date" for="x_Purchase_Date" class="col-sm-4 control-label ewLabel"><?php echo $a_purchases->Purchase_Date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $a_purchases->Purchase_Date->CellAttributes() ?>>
<span id="el_a_purchases_Purchase_Date">
<input type="text" data-table="a_purchases" data-field="x_Purchase_Date" data-format="9" name="x_Purchase_Date" id="x_Purchase_Date" placeholder="<?php echo ew_HtmlEncode($a_purchases->Purchase_Date->getPlaceHolder()) ?>" value="<?php echo $a_purchases->Purchase_Date->EditValue ?>"<?php echo $a_purchases->Purchase_Date->EditAttributes() ?>>
<?php if (!$a_purchases->Purchase_Date->ReadOnly && !$a_purchases->Purchase_Date->Disabled && !isset($a_purchases->Purchase_Date->EditAttrs["readonly"]) && !isset($a_purchases->Purchase_Date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fa_purchasesadd", "x_Purchase_Date", "%Y/%m/%d %H:%M:%S");
</script>
<?php } ?>
</span>
<?php echo $a_purchases->Purchase_Date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_purchases->Supplier_ID->Visible) { // Supplier_ID ?>
	<div id="r_Supplier_ID" class="form-group">
		<label id="elh_a_purchases_Supplier_ID" for="x_Supplier_ID" class="col-sm-4 control-label ewLabel"><?php echo $a_purchases->Supplier_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $a_purchases->Supplier_ID->CellAttributes() ?>>
<?php if ($a_purchases->Supplier_ID->getSessionValue() <> "") { ?>
<span id="el_a_purchases_Supplier_ID">
<span<?php echo $a_purchases->Supplier_ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $a_purchases->Supplier_ID->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_Supplier_ID" name="x_Supplier_ID" value="<?php echo ew_HtmlEncode($a_purchases->Supplier_ID->CurrentValue) ?>">
<?php } else { ?>
<span id="el_a_purchases_Supplier_ID">
<?php $a_purchases->Supplier_ID->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$a_purchases->Supplier_ID->EditAttrs["onchange"]; ?>
<select data-table="a_purchases" data-field="x_Supplier_ID" data-value-separator="<?php echo ew_HtmlEncode(is_array($a_purchases->Supplier_ID->DisplayValueSeparator) ? json_encode($a_purchases->Supplier_ID->DisplayValueSeparator) : $a_purchases->Supplier_ID->DisplayValueSeparator) ?>" id="x_Supplier_ID" name="x_Supplier_ID"<?php echo $a_purchases->Supplier_ID->EditAttributes() ?>>
<?php
if (is_array($a_purchases->Supplier_ID->EditValue)) {
	$arwrk = $a_purchases->Supplier_ID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($a_purchases->Supplier_ID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $a_purchases->Supplier_ID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($a_purchases->Supplier_ID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($a_purchases->Supplier_ID->CurrentValue) ?>" selected><?php echo $a_purchases->Supplier_ID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "id":
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
}
$lookuptblfilter = (isset($_GET["Supplier_Number"])) ? "Supplier_Number = '".$_GET["Supplier_Number"]."'" : "";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$a_purchases->Supplier_ID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$a_purchases->Supplier_ID->LookupFilters += array("f0" => "`Supplier_Number` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$a_purchases->Lookup_Selecting($a_purchases->Supplier_ID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $a_purchases->Supplier_ID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_Supplier_ID" id="s_x_Supplier_ID" value="<?php echo $a_purchases->Supplier_ID->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $a_purchases->Supplier_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_purchases->Notes->Visible) { // Notes ?>
	<div id="r_Notes" class="form-group">
		<label id="elh_a_purchases_Notes" for="x_Notes" class="col-sm-4 control-label ewLabel"><?php echo $a_purchases->Notes->FldCaption() ?></label>
		<div class="col-sm-8"><div<?php echo $a_purchases->Notes->CellAttributes() ?>>
<span id="el_a_purchases_Notes">
<input type="text" data-table="a_purchases" data-field="x_Notes" name="x_Notes" id="x_Notes" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($a_purchases->Notes->getPlaceHolder()) ?>" value="<?php echo $a_purchases->Notes->EditValue ?>"<?php echo $a_purchases->Notes->EditAttributes() ?>>
</span>
<?php echo $a_purchases->Notes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_purchases->Total_Amount->Visible) { // Total_Amount ?>
	<div id="r_Total_Amount" class="form-group">
		<label id="elh_a_purchases_Total_Amount" for="x_Total_Amount" class="col-sm-4 control-label ewLabel"><?php echo $a_purchases->Total_Amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $a_purchases->Total_Amount->CellAttributes() ?>>
<span id="el_a_purchases_Total_Amount">
<input type="text" data-table="a_purchases" data-field="x_Total_Amount" name="x_Total_Amount" id="x_Total_Amount" size="30" placeholder="<?php echo ew_HtmlEncode($a_purchases->Total_Amount->getPlaceHolder()) ?>" value="<?php echo $a_purchases->Total_Amount->EditValue ?>"<?php echo $a_purchases->Total_Amount->EditAttributes() ?>>
<?php if (!$a_purchases->Total_Amount->ReadOnly && !$a_purchases->Total_Amount->Disabled && @$a_purchases->Total_Amount->EditAttrs["readonly"] == "" && @$a_purchases->Total_Amount->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x_Total_Amount').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '0', aForm: false});
</script>
<?php } ?>
</span>
<?php echo $a_purchases->Total_Amount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_purchases->Total_Payment->Visible) { // Total_Payment ?>
	<div id="r_Total_Payment" class="form-group">
		<label id="elh_a_purchases_Total_Payment" for="x_Total_Payment" class="col-sm-4 control-label ewLabel"><?php echo $a_purchases->Total_Payment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $a_purchases->Total_Payment->CellAttributes() ?>>
<span id="el_a_purchases_Total_Payment">
<input type="text" data-table="a_purchases" data-field="x_Total_Payment" name="x_Total_Payment" id="x_Total_Payment" size="30" placeholder="<?php echo ew_HtmlEncode($a_purchases->Total_Payment->getPlaceHolder()) ?>" value="<?php echo $a_purchases->Total_Payment->EditValue ?>"<?php echo $a_purchases->Total_Payment->EditAttributes() ?>>
<?php if (!$a_purchases->Total_Payment->ReadOnly && !$a_purchases->Total_Payment->Disabled && @$a_purchases->Total_Payment->EditAttrs["readonly"] == "" && @$a_purchases->Total_Payment->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x_Total_Payment').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '0', aForm: false});
</script>
<?php } ?>
</span>
<?php echo $a_purchases->Total_Payment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_purchases->Total_Balance->Visible) { // Total_Balance ?>
	<div id="r_Total_Balance" class="form-group">
		<label id="elh_a_purchases_Total_Balance" for="x_Total_Balance" class="col-sm-4 control-label ewLabel"><?php echo $a_purchases->Total_Balance->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-8"><div<?php echo $a_purchases->Total_Balance->CellAttributes() ?>>
<span id="el_a_purchases_Total_Balance">
<input type="text" data-table="a_purchases" data-field="x_Total_Balance" name="x_Total_Balance" id="x_Total_Balance" size="30" placeholder="<?php echo ew_HtmlEncode($a_purchases->Total_Balance->getPlaceHolder()) ?>" value="<?php echo $a_purchases->Total_Balance->EditValue ?>"<?php echo $a_purchases->Total_Balance->EditAttributes() ?>>
<?php if (!$a_purchases->Total_Balance->ReadOnly && !$a_purchases->Total_Balance->Disabled && @$a_purchases->Total_Balance->EditAttrs["readonly"] == "" && @$a_purchases->Total_Balance->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x_Total_Balance').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '0', aForm: false});
</script>
<?php } ?>
</span>
<?php echo $a_purchases->Total_Balance->CustomMsg ?></div></div>
	</div>
<?php } ?>
<span id="el_a_purchases_Date_Added">
<input type="hidden" data-table="a_purchases" data-field="x_Date_Added" name="x_Date_Added" id="x_Date_Added" value="<?php echo ew_HtmlEncode($a_purchases->Date_Added->CurrentValue) ?>">
</span>
<span id="el_a_purchases_Added_By">
<input type="hidden" data-table="a_purchases" data-field="x_Added_By" name="x_Added_By" id="x_Added_By" value="<?php echo ew_HtmlEncode($a_purchases->Added_By->CurrentValue) ?>">
</span>
</div>
<?php
	if (in_array("a_purchases_detail", explode(",", $a_purchases->getCurrentDetailTable())) && $a_purchases_detail->DetailAdd) {
?>
<?php if ($a_purchases->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("a_purchases_detail", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "a_purchases_detailgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-4 col-sm-8">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-danger ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $a_purchases_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fa_purchasesadd.Init();
</script>
<?php
$a_purchases_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">
$(document).on("updatedone", function(e, args) {
	if (args.target.id == "x_Supplier_ID") {
		$("#x_Supplier_ID").on("change", function() {
		<?php if (isset($_GET["Supplier_Number"])) { ?>

		//$("#x_Supplier_ID option[value='']").remove();
		//$(args.target).val("<?php echo $_GET["Supplier_Number"]; ?>");
		//$(args.target).attr('disabled','disabled');

		<?php } ?>			
		});

		//$("#x_Supplier_ID").trigger("change");
	}
});
$(document).ready(function() {
	$("#x_Total_Balance").blur(function() {
		var total_amount = $("#x_Total_Amount").autoNumeric('get');
		var total_payment = $("#x_Total_Payment").autoNumeric('get');
		var total_balance = total_amount - total_payment;
		$("#x_Total_Balance").val(total_balance);
	});
	AdjustCSS();
});

function AdjustCSS() {
	$("input[data-field='x_Purchasing_Quantity'], input[data-field='x_Purchasing_Price'], input[data-field='x_Selling_Price'], input[data-field='x_Purchasing_Total_Amount']").css('min-width', '120px');
	$("input[data-field='x_Purchasing_Quantity'], input[data-field='x_Purchasing_Price'], input[data-field='x_Selling_Price'], input[data-field='x_Purchasing_Total_Amount']").css('max-width', '160px');
	$("input[data-field='x_Purchasing_Quantity'], input[data-field='x_Purchasing_Price'], input[data-field='x_Selling_Price'], input[data-field='x_Purchasing_Total_Amount']").css('text-align', 'right');
}
$(function() {
	$(document).on('change', 'select[data-field="x_Stock_Item"]', function (e) {
		generateSelectedAreas();		
	});

	function generateSelectedAreas() {
		var selectedValues=[];
		$('select[data-field="x_Stock_Item"] option:selected').each(function () {
		   var select = $(this).parent(),
		   optValue = $(this).val();            
		   if($(this).val()!=''){
			   $('select[data-field="x_Stock_Item"]').not(select).children().filter(function(e){
				   if($(this).val()==optValue) 					 						
					   return e
			   }).remove();
		   }
		});
	}
});

function SetFocusTextBox(name) {
	$("#x_"+ name +"").select(); 
}

function SetFocusPurchasingQuantity(event) {
	var elm_name = $(event.target).attr('name');
	var start_pos = elm_name.indexOf('x') + 1;
	var end_pos = elm_name.indexOf('_',start_pos);
	var idx = elm_name.substring(start_pos,end_pos)
	$("#x" + idx + "_Purchasing_Quantity").select();  
}

function GetAmountTotal() {
	var total_amount = $("#x_Total_Amount").val(GetPurchasingTotal());
	return total_amount;
}

function GetBalanceTotal() {
	var total_amount = $("#x_Total_Amount").autoNumeric('get');
	var total_payment = $("#x_Total_Payment").autoNumeric('get');
	var total_balance = total_amount - total_payment;
	$("#x_Total_Balance").val(total_balance);
	if (total_balance < 0) {
		$("#x_Total_Payment").val(total_amount);
		$("#x_Total_Balance").val("0");
	}
}

function CalculateGrid(event) {
	var elm_name = $(event.target).attr('name');
	var start_pos = elm_name.indexOf('x') + 1;
	var end_pos = elm_name.indexOf('_',start_pos);
	var idx = elm_name.substring(start_pos,end_pos)
	$("#x" + idx + "_Purchasing_Total_Amount").val($("#x" + idx + "_Purchasing_Quantity").autoNumeric('get') *
	$("#x" + idx + "_Purchasing_Price").autoNumeric('get'));  
	$("#x_Total_Amount").val(GetPurchasingTotal());
}

function GetPurchasingTotal() {
	var fobj = fa_purchases_detailgrid.GetForm(), $fobj = $(fobj);
	fa_purchases_detailgrid.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var val_elm, addcnt = 0;
	var $k = $fobj.find("#" + fa_purchases_detailgrid.FormKeyCountName); 
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; 
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	var total_amount = 0;
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !fa_purchases_detailgrid.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			val_elm = $("#x" + infix + "_Purchasing_Total_Amount").autoNumeric('get');
			total_amount += +(val_elm);
			ew_ElementsToRow(fobj);
		}
	}
	return total_amount;
}
</script>
<?php if (MS_ENTER_MOVING_CURSOR_TO_NEXT_FIELD) { ?>
<script type="text/javascript">
$(document).ready(function(){$("#fa_purchasesadd:first *:input[type!=hidden]:first").focus(),$("input").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()}),$("select").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()}),$("radio").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()})});
</script>
<?php } ?>
<?php if ($a_purchases->Export == "") { ?>
<script type="text/javascript">
$('#btnAction').attr('onclick', 'return alertifyAdd(this)'); function alertifyAdd(obj) { <?php global $Language; ?> if (fa_purchasesadd.Validate() == true ) { alertify.confirm("<?php echo $Language->Phrase('AlertifyAddConfirm'); ?>", function (e) { if (e) { $(window).unbind('beforeunload'); alertify.success("<?php echo $Language->Phrase('AlertifyAdd'); ?>"); $("#fa_purchasesadd").submit(); } }).set("title", "<?php echo $Language->Phrase('AlertifyConfirm'); ?>").set("defaultFocus", "cancel").set('oncancel', function(closeEvent){ alertify.error('<?php echo $Language->Phrase('AlertifyCancel'); ?>');}).set('labels', {ok:'<?php echo $Language->Phrase("MyOKMessage"); ?>!', cancel:'<?php echo $Language->Phrase("MyCancelMessage"); ?>'}); } return false; }
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$a_purchases_add->Page_Terminate();
?>
