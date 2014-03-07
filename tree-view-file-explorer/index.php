<!DOCTYPE html>
<html>
<head>
<title>Tree View - File System</title>
<link rel="stylesheet" href="css/aciTree.css" type="text/css">
<link rel="stylesheet" href="css/demo.css" type="text/css">
<style type="text/css">
#currStatus {
	color:red;
	margin-top:1em;
}
</style>
</head>
<body>
	<!-- The div that will contain the ACI Tree -->
	<div id="fsTree" class="aciTree"></div>
	<button id="btnGetTreeView">Get Tree View</button>
	<button id="btnRefreshTreeView">Refresh Tree View</button>
	<div id='currStatus'></div>
</body>
<!-- Loading jQuery -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<!-- Loading the aciTree plugin -->
<script src="js/jquery.aciPlugin.min.js" type="text/javascript"></script>

<!-- Loading the  aciTree core-->
<script src="js/jquery.aciTree.core.js" type="text/javascript"></script>

<!-- Loading the aciTree selectable plugin -->
<script src="js/jquery.aciTree.selectable.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
    $currStatus = $('#currStatus');
    // Makes the ajax call and fetches the json for the resource tree.
    $('#btnGetTreeView').click(function(){
		$("#fsTree").aciTree({
		    ajax : {
			    type : 'POST',
			    url : 'index.php',
			    data : {
				    // Notice that this is the method name that
				    // we wish to call on the server side.
				    'method' : 'getJsonTree'
			    }
		    }
	    });
    });

    // Refreshing the tree view - Destroy and recreate
    $('#btnRefreshTreeView').click(function(){
		var api = $('#fsTree').aciTree('api');
        api.unload(null, {
            success: function() {
                this.ajaxLoad(null);
                // Triggering the click handler of the Get Tree View button.
                // This will make the ajax call again and bind the tree...
                $('#btnGetTreeView').trigger('click');
                $currStatus.text('');
            }
        });
    });

    // ACI Tree - event handler.
    $('#fsTree').on('acitree', function(event, aciApi, item, eventName, opt) {
        switch (eventName) {
            case 'focused':
            case 'selected' :
                // Fired when an item in the tree is selected.
                if(item) {
                	$currStatus.text('Selected - ' + item.context.innerText);
                } 
        }
    });
});
</script>
</html>


<?php
/**
 * Represents each node in the aci tree jquery plugin
 * 
 * @author abijeet
 */
class NodeList {
	public $id, $label, $inode, $open, $icon, $branch;
	private $openIfBranch;

	/**
	 * Constructor for NodeList
	 *
	 * @param string $label
	 *        	Label of the node
	 * @param boolean $open
	 *        	If this is a branch, should it be open
	 * @param string $icon
	 *        	Icon for the node
	 */
	public function __construct($label, $open, $id = '', $icon = '') {
		if ($id) {
			$this->id = $id;
		}
		$this->label = basename($label);
		$this->open = false;
		$this->openIfBranch = $open;
		$this->icon = $icon;
		$this->inode = false;
	}
	
	/**
	 * We are setting a branch using this function. It takes the NodeList array as
	 * parameter and appends it as a branch. It also modified the original label to
	 * include a number that depicts the number of nodes in the branch.
	 */
	public function setBranch($branch) {
		$this->branch = $branch;
		$cntBranch = count($branch);
		if ($cntBranch > 0) {
			$this->inode = true;
			$this->label .= ' [' . $cntBranch . ']';
		}
		$this->open = $this->openIfBranch;
	}
}


if (! empty($_POST['method'])) {
	// Do some check before handling the POST data.
	$methodToCall = $_POST['method'];
	ob_clean();
	// Call the method requested from the client side.
	$result = call_user_func($methodToCall);
	die(json_encode($result));
}

/**
 * Function that is call by the JQUERY post.
 * 
 * @return multitype:NodeList
 */
function getJsonTree() {
	// Folder Path from where we are going to show the tree view.
	$pathToGetAciTree = './cakephp';
	$jsonTree = jsonForResTree($pathToGetAciTree);
	return $jsonTree;
}

/**
 * Function that given a path, returns an array of nodeList
 * This can then be converted to a json format.
 * 
 * @param $path Path
 *        	of the folder from which to retrieve
 * @return multitype:NodeList Returns the json tree
 */
function jsonForResTree($path) {
	$dirArray = getAllFilesAndFolders($path);
	$nodeArray = array ();
	$node = '';
	$cnt = count($dirArray);
	for($i = 0; $i < $cnt; ++ $i) {
		$node = new NodeList($dirArray[$i], false);
		if (is_dir($dirArray[$i])) {
			// Recursion - It's a folder, get the array of nodeList for it.
			$nodeList = jsonForResTree($dirArray[$i]);
			// Add it as branch
			$node->setBranch($nodeList);
		}
		$nodeArray[] = $node;
	}
	return $nodeArray;
}

/**
 * Gets all files and folders from the specified path
 * 
 * @param unknown $path
 *        	Path of the folder from where files and folders are to be retrieved
 * @return multitype:
 */
function getAllFilesAndFolders($path) {
	if (! is_dir($path)) {
		return array ();
	}
	$path = $path . DIRECTORY_SEPARATOR . '*';
	return glob($path, GLOB_NOSORT);
}

?>