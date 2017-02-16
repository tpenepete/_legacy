<?php
defined('TPT_INIT') or die('Access Denied');

function fix_LS($content, $base_url) {
	$content = preg_replace('#src="([^/](?!avascript)(?!ttp://)(?!ww.))#i', 'src="'.$base_url.'$1', $content);
	$content = preg_replace('#href="([^/](?!avascript)(?!ttp://)(?!ww.))#i', 'href="'.$base_url.'$1', $content);
	return $content;
}

function _uploadFile($file, $action, $path) {
	$upload_status = 100;
	$fname = $file['name'];
	if($file['name']!='') {
		if($path[strlen($path)-1] != DIRECTORY_SEPARATOR)
			$path .= DIRECTORY_SEPARATOR;
		$path_file = $path.preg_replace('#\W#', '_', substr($file['name'], 0, strpos($file['name'], '.'))).substr($file['name'], strpos($file['name'], '.'));
		if ($file["error"] == UPLOAD_ERR_OK) {
			if(!is_dir($path)) {
				$oldumask = umask(0);
				if(!mkdir($path, 0777, true))
					$upload_status = 50;
				umask($oldumask);
			}
			if($upload_status == 100) {
				if(is_file($path_file)) {
					if($action == '1') {
						if(move_uploaded_file($file['tmp_name'], $path_file)) {
							$upload_status = 2;
						} else {
							$upload_status = 10;
						}
					} else if($action == '2') {
						$i=0;
						while(is_file($path.substr($file['name'], 0, strpos($file['name'], '.')).$i.substr($file['name'], strpos($file['name'], '.'))))
							$i++;
						if(rename($path.$file['name'], $file['name'].substr($file['name'], 0, strpos($file['name'], '.')).$i.substr($file['name'], strpos($file['name'], '.')))) {
							if(move_uploaded_file($file['tmp_name'], $path_file)) {
								$upload_status = 3;
								$fname = substr($file['name'], 0, strpos($file['name'], '.')).$i.substr($file['name'], strpos($file['name'], '.'));
							} else {
								$upload_status = 10;
								$fname = '';
							}
						} else {
							$upload_status = 30;
							$fname = '';
						}
					} else if($action == '3') {
						$i=0;
						while(is_file($path.preg_replace('#\W#', '_', substr($file['name'], 0, strpos($file['name'], '.'))).$i.substr($file['name'], strpos($file['name'], '.'))))
							$i++;
						$path_file = $path.preg_replace('#\W#', '_', substr($file['name'], 0, strpos($file['name'], '.'))).$i.substr($file['name'], strpos($file['name'], '.'));
						if(move_uploaded_file($file['tmp_name'], $path.preg_replace('#\W#', '_', substr($file['name'], 0, strpos($file['name'], '.'))).$i.substr($file['name'], strpos($file['name'], '.')))) {
							$upload_status = 4;
							$path_file = $path.preg_replace('#\W#', '_', substr($file['name'], 0, strpos($file['name'], '.'))).$i.substr($file['name'], strpos($file['name'], '.'));
							$fname = preg_replace('#\W#', '_', substr($file['name'], 0, strpos($file['name'], '.'))).$i.substr($file['name'], strpos($file['name'], '.'));
							$oldumask = umask(0);
							chmod($path_file, 0777);
							umask($oldumask);
						} else {
							$upload_status = 10;
							$fname = '';
						}
					} else {
						$upload_status = 5;
						$fname = '';
					}
				} else {
					if(move_uploaded_file($file['tmp_name'], $path_file)) {
						$upload_status = 1;
						$fname = preg_replace('#\W#', '_', substr($file['name'], 0, strpos($file['name'], '.'))).substr($file['name'], strpos($file['name'], '.'));;
						$oldumask = umask(0);
						chmod($path_file, 0777);
						umask($oldumask);
					} else {
						$upload_status = 10;
						$fname = '';
					}
				}
			}
		} else {
			$upload_status = 100;
			$fname = '';
		}
	}
	
	if($upload_status == 1) {
		//$app->enqueueMessage(JText::_( "Image upload success.", 'message'));
	} else if($upload_status == 2) {
		//$app->enqueueMessage(JText::_( "Old image overwritten successfully.", 'message'));
		//$app->enqueueMessage(JText::_( "Image upload success.", 'message'));
	} else if($upload_status == 3) {
		//$app->enqueueMessage(JText::_( "Old $ftypel renamed successfully.", 'message'));
		//$app->enqueueMessage(JText::_( "Image upload success.", 'message'));
	} else if($upload_status == 4) {
		//$app->enqueueMessage(JText::_( "Image uploaded and renamed successfully to: $path_file", 'message'));
		//$app->enqueueMessage(JText::_( "Image upload success.", 'message'));
	} else if($upload_status == 5) {
		//$app->enqueueMessage(JText::_( "Image with that name already exists. File upload skipped according to user setting.", 'message'));
	} else if($upload_status == 10) {
		//$app->enqueueMessage(JText::_( "Error moving the uploaded image file." , 'warning'));
		$fname = $cur_file;
		die('Error uploading file. Status: '.$upload_status);
	} else if($upload_status == 30) {
		//$app->enqueueMessage(JText::_( "Error renaming old image file: $path_file" , 'warning'));
		$fname = $cur_file;
		die('Error uploading file. Status: '.$upload_status);
	} else if($upload_status == 50) {
		//$app->enqueueMessage(JText::_( "Error creating image upload directory: $path" , 'warning'));
		$fname = $cur_file;
		die('Error uploading file. Status: '.$upload_status);
	} else if($upload_status == 100) {
		//$app->enqueueMessage(JText::_( "A problem occured with the image upload.", 'warning'));
		$fname = $cur_file;
		die('Error uploading file. Status: '.$upload_status);
	}
	
	return $fname;
}

/* Parses a template from the HTMLtemplates folder and creates helper JS used for parsing the template in the DOM builder
 * function hypParseHTMLtoJS($arrFile, $elmsVar, $exPHPvars, $replacePHPvars, $regexToJSFunction, $exAttributes, $exElementsRegEx, $templateReplaceRegex)
* $arrFile - the template file broken down to an array one line at a time (using the file() function)
* $elmsVar - a global javascript variable that will hold a reference to the parent object in the DOM
* $exPHPvars - globalize those php vars and insert value whenever encountered in the code :)
* $replacePHPvars - a $key=>$value array. normally the strings are the same  
* $regexToJSFunction - searches the template line for the pattern and replaces the element with a reference to a function of the following syntax:
*                         array(pattern=>array('function_pointer'=>function_pointer, function_arguments=>array(args)))
* $exAttributes - exclude the attributes in the passed array from being added or parsed to the final object
* $exElementsRegEx - exclude elements which match any of the passed array of patterns
* $templateReplaceRegex - run a preg_replace with the passed pattern=>replacement array before parsing the line
*/
function hypParseHTMLtoJS($arrFile, $elmsVar, $exPHPvars, $replacePHPvars, $regexToJSFunction, $exAttributes, $exElementsRegEx, $templateReplaceRegex) {
	foreach($exPHPvars as $var) // make the passed array of variable names - global php vars
		global $$var;
	
	/////////////////////// INIT PARSER
	$linenum = 0; // the current parsing line
	$elms = array(); // an array of processed elements 
	$currentParent = false; // reference to the current parent in the DOM
	$output = ''; // the JS generated for output. to be placed inside <script></script>
	if(!empty($exAttributes)) {
		for($i=0; $i<count($exAttributes); $i++)
			$exAttributes[$i] = '"'.$exAttributes[$i].'"';
		$excludeAttributes = '['.implode(', ', $exAttributes).']';
	} else {
		$excludeAttributes = 'null';
	}
		
	$wait_for_closing_tag = false; // trigger the parser in search for starting tag mode
	/////////////////////// END INIT PARSER
	
	/////////////////////// BEGIN PARSING ONE LINE AT A TIME
	foreach($arrFile as $line) {
		$line = trim($line); // trim input
		foreach($templateReplaceRegex as $pattern=>$replace) { // search and replace in the raw code from the func parameter
			$line = preg_replace($pattern, $replace, $line);
		}
		preg_match('#<[a-zA-Z]+.*?(?<!\?)>#', $line, $open); // line has an opening OR selfclosed tag
		preg_match('#</[a-zA-Z]+>#', $line, $close); // line has a closing tag
		preg_match('#<[a-zA-Z]+.*?(?<!\?)/>#', $line, $selfclose); // line is selfclosed
		$skip = false; 
		foreach($exElementsRegEx as $regex) { // skip elements which contain the patterns in this func parameter
			preg_match("#$regex#", $line, $exElm);
			if(!empty($exElm))
				$skip = true;
		}
		
		if(!$skip) { // begin building element type data
			$func = false;
			if(!$wait_for_closing_tag) { // if parent element was not constructed from a function then we can parse the current element
				if(!empty($open)) { // process a new element
					$output .= $elmsVar.'['.count($elms).'] = '; // START THE ELEMENT. $elmsVar is a func parameter which stores the global JS var name that has its 0 index set to a reference to the master DOM object $elms starts as an empty array so the first processed element is in fact the master element
					
					foreach($exPHPvars as $var) { // replace with the globalized $$var
						$line = preg_replace("#<\?php echo \\$$var; \?>#", $$var, $line);
					}
						
					foreach($regexToJSFunction as $regex=>$function) { // see if element needs to be created from a JS function
						preg_match("#$regex#", $line, $rtof);
						if(!empty($rtof)) { // if line contains a match from any of the supplied patterns...
							$func = true; // ... the element is constructed from a function
							if(!empty($function['function_arguments'])) {
								for($i=0; $i<count($function['function_arguments']); $i++)
									$function['function_arguments'][$i] = '"'.$function['function_arguments'][$i].'"';
								$funcArguments = '['.implode(', ', $function['function_arguments']).']'; // prep the argument list
							} else {
								$funcArguments = '[]'; // the function does not accept arguments
							}
								
							if(!empty($currentParent)) { // unfinished...
								$output .= $elmsVar.'['.$currentParent->index.'].element_children['.$elmsVar.'['.$currentParent->index.'].element_children.length] = ';
								// start writing the element DOM builder code
							} else {
								 // what happens if there is NO parent element :( well the master element cannot be constructed from a function
							}
							
							// continue writing the code and assign the element the approptiate DOM builder arguments
							$output .= '{element_type: "function", function_pointer: '.$function['function_pointer'].', function_arguments: '.$funcArguments.', element_children: []};'."\r\n";
							
							if(empty($selfclose)) { // if the element does not self close this will trigger the parser to skip the next elements (since the element gets built using a JS function we cannot parse its children here)
								$wait_for_closing_tag = true;
							}
						}
						// the element is not constructed from a function so $func stays false
					}
					
					if(!$func) { // the element is not constructed from a function so parse its tag name and attributes
						if(!empty($currentParent)) { // if this is not the master element we can assign it a parent
							$parentElm = $elmsVar.'['.$currentParent->index.']'; // 
						} else {
							$parentElm = 'null';
						}
						
						$replacementVars = array(); // the following will discover the correct arguments for handling the dynamic JS indexes usually used for incrementing the rows 
						foreach($replacePHPvars as $var=>$replacement) {
							preg_match_all("#<\?php echo \\$$var; \?>#", $line, $varFound, PREG_SET_ORDER); // php does not resolve double dollar sign variables between ""
							if(!empty($varFound)) {
								foreach($varFound as $useless)
									$replacementVars[] = $replacement;
							}
						}
						if(!empty($replacementVars)) {
							for($i=0; $i<count($replacementVars); $i++)
								$replacementVars[$i] = '"'.$replacementVars[$i].'"';
							$replacementVars = '['.implode(', ', $replacementVars).']'; // here the discovered vars are stacked as a JS array
						} else {
							$replacementVars = 'null';
						}
						
						$line = str_replace('\'', '\\\'', $line); // escape quotes 
						$line = str_replace('<?php', '<\'+\'?php', $line); // split the php tags
						//$line = str_replace('?//>', '?\'+\'>', $line);
						
						if(!empty($close)) { // if the element has its closing tag on the same line then copy the rest of the HTML (its innerHTML) to the innerHTML DOM builder parameter
							$innerHTML = '\''.str_replace(array($open[0], $close[0]), '', str_replace('\\\'', '\'', $line)).'\'';
						} else {
							$innerHTML = 'null';
						}
							
						$output .= 'hypParseHTML(\''.$line.'\', '.$parentElm.', '.$innerHTML.', '.$replacementVars.', '.$excludeAttributes.');'."\r\n";
					}
					
					// add the element to the php array
					if(empty($currentParent)) { // if it IS the master element
						$elms[] = new stdClass();
						$elms[count($elms)-1]->parent = false; 
						$elms[count($elms)-1]->index = count($elms)-1;
					} else {
						$elms[] = new stdClass();
						$elms[count($elms)-1]->parent = $currentParent; 
						$elms[count($elms)-1]->index = count($elms)-1;
					}
					
					if(empty($selfclose) && !$func) { // if not selfclosed and not a function then set this to be the current parent for the following elements
						$currentParent = $elms[count($elms)-1];
					}
				} // END if(!empty($open)) { // process a new element
			} // END if(!$wait_for_closing_tag) { // see below
			
			if(!empty($close)) { // handle a present closing tag
				if(!$wait_for_closing_tag) // if not within function element children
					$currentParent = $currentParent->parent; // then we can ascend the current parent one level up - we have completed parsing a tag's children and close the tag
					
				if(empty($open)) { // prolly a mistake $wait_for_closing_tag = false; should always happen :?
					$wait_for_closing_tag = false;
				}
			}
		} // END if(!$skip) {
			
		$linenum++; // increment line counter
	}
	
	return $output;
}

/*
function hypParseHTML(HTMLString, parentObject, innerHTML, replacePHPwithJSVar, exAttributes) {
	var elm = {};

	var tagRE = new RegExp("<([a-zA-Z]+)((.(?!<\/)(?!<[a-zA-Z]))*?[^?])?>");
	var attributesRE = new RegExp('<\\?php[\\s\\S]*?\\?>|\\s+(\\w+)="([^"<]*(?:<\\?php[\\s\\S]*?\\?>[^<"]*)*)"', "g");
	var attributeComponentsRE = new RegExp('(\\w+)="(.*?)"');
	var phpRE = new RegExp('<\\?php.*?\\?>', "g");

	var mtch = HTMLString.match(tagRE);
	if(!mtch)
		return false;

	elm.element_type = mtch[1];
	elm.element_children = [];
	elm.element_attributes = [];
	elm.inner_html = innerHTML;

	if(mtch[2]) {
		var tagAttributes = [];

		while(tagAttributes[tagAttributes.length] = attributesRE.exec(mtch[0]));

		if(((typeof(tagAttributes) == 'object') || (typeof(tagAttributes) == 'array')) && tagAttributes) {
			tagAttributes.pop();

			for(var i=0; i<tagAttributes.length; i++) {
				var PHPtoJSCounter = 0;

				var attributeContent = {attribute_name: '', attribute_value_raw: '', attribute_value: []};
				var dismiss = false;

				if(tagAttributes[i][1]) {
					attributeContent.attribute_name = tagAttributes[i][1];
					attributeContent.attribute_value_raw = tagAttributes[i][2];
					if(((typeof(exAttributes) == 'object') || (typeof(exAttributes) == 'array')) && exAttributes) {
						for(var j=0; j<exAttributes.length; j++) {
							if(exAttributes[j] == attributeContent.attribute_name)
								dismiss = true;
						}
					}

					attributeContent.attribute_values = [];

					var attributePHPContent = attributeContent.attribute_value_raw.match(phpRE);
					if(attributePHPContent) {
						var splitValue = attributeContent.attribute_value_raw.split(phpRE);

						for(var j=0; j<splitValue.length; j++) {
							if(j != splitValue.length-1) {
								attributeContent.attribute_values[attributeContent.attribute_values.length] = {value_type: 'simple', text: splitValue[j]};
								if((((typeof(replacePHPwithJSVar) == 'object') || (typeof(replacePHPwithJSVar) == 'array')) && replacePHPwithJSVar) && replacePHPwithJSVar[PHPtoJSCounter]) {
									attributeContent.attribute_values[attributeContent.attribute_values.length] = {value_type: 'evaluated', varname: replacePHPwithJSVar[PHPtoJSCounter]};
									PHPtoJSCounter++;
								} else {
									attributeContent.attribute_values[attributeContent.attribute_values.length] = {value_type: 'simple', text: ''};
								}
							} else {
								attributeContent.attribute_values[attributeContent.attribute_values.length] = {value_type: 'simple', text: splitValue[j]};
							}
						}
					} else {
						attributeContent.attribute_values = [{value_type: 'simple', text: attributeContent.attribute_value_raw}];
					}
				} else {
					dismiss = true;
				}

				if(!dismiss) {
					elm.element_attributes[elm.element_attributes.length] = attributeContent;
				}
			}
		}
	}

	if((typeof(parentObject) == 'object') && parentObject) {
		if(((typeof(parentObject.element_children) == 'object') || (typeof(parentObject.element_children) == 'array')) && parentObject.element_children) {
			parentObject.element_children[parentObject.element_children.length] = elm;
		}
	}

	return elm;
}

function hypBuildHTML(htmlObject, parentElement, exParam) {

	if(!htmlObject || (typeof(htmlObject) != 'object'))
		return;

	var newElm;

	if(htmlObject.element_type == 'function') {
		var args = [];
		for(var i=0; i<htmlObject.function_arguments.length; i++) {
			args[args.length] = window[htmlObject.function_arguments[i]];
		}
		newElm = htmlObject.function_pointer.apply(this, args);
	} else {
		newElm = document.createElement(htmlObject.element_type);

		if(htmlObject.inner_html)
			newElm.innerHTML = htmlObject.inner_html;

		for(var i=0; i<htmlObject.element_attributes.length; i++) {
			var attrVal = '';
			for(var j=0; j<htmlObject.element_attributes[i].attribute_values.length; j++) {
				if(htmlObject.element_attributes[i].attribute_values[j].value_type == 'simple') {
					attrVal += htmlObject.element_attributes[i].attribute_values[j].text;
				} else if(htmlObject.element_attributes[i].attribute_values[j].value_type == 'evaluated') {
				    eval('attrVal += '+htmlObject.element_attributes[i].attribute_values[j].varname+';');
				}
			}

			if(htmlObject.element_attributes[i].attribute_name == 'class') {
				newElm['className'] = attrVal;
			} else if(htmlObject.element_attributes[i].attribute_name == 'style') {
				newElm['style']['cssText'] = attrVal;
			} else if(htmlObject.element_attributes[i].attribute_name == 'onclick') {
				newElm.setAttribute('onclick', attrVal);
			} else {
				newElm[htmlObject.element_attributes[i].attribute_name] = attrVal;
			}
		}
	}

	for(var i=0; i<htmlObject.element_children.length; i++) {
		var newElmChild = hypBuildHTML(htmlObject.element_children[i], newElm, exParam);
	}

    if(parentElement !== null)
        parentElement.appendChild(newElm);
	return newElm;
}

function buildElementName(exParam) {
    if(!isDOMNode(exParam))
        return;

    var row = getSpecialParent(exParam, 'TR');
    var tbody = getSpecialParent(row, 'TBODY');
    var table = getSpecialParent(tbody, 'TABLE');

    var rows = getChildElements(tbody);
    var index = '';
    var suffix = '';
    if(row.className.match(/^(.*[\s]+)?removable([\s]+.*)?$/im)) {
        for(var i=0, ilen=rows.length; i<ilen; i++) {
            if(row===rows[i]) {
                index = i+1;
                break;
            }
        }
        suffix = exParam.title;
    } else {
        index = rows.length;
    }

    var nameRegEx = new RegExp(/^(.*\[)(\].*)$/mi);

    return tbody.id.replace(nameRegEx, '$1'+(index)+'$2')+suffix;
}

function buildTbodyId(tbody) {
    if(!isDOMNode(tbody))
        return;

    var prow = getSpecialParent(tbody, 'TR');
    var ptbody = getSpecialParent(prow, 'TBODY');
    var rows = getChildElements(ptbody);

    var index;
    for(var i=0, ilen=rows.length; i<ilen; i++) {
        if(prow===rows[i]) {
            index = i+1;
            break;
        }
    }

    var nameRegEx = new RegExp(/^(.*\[)(\].*)$/mi);

    return ptbody.id.replace(nameRegEx, '$1'+(index)+'$2')+tbody.title;
}

function getSpecialParent(refElm, elmType, nonstrict) {
    elmType = elmType.toUpperCase();
    spRegs = {TR:/^(.*[\s]+)?removable([\s]+.*)?$/im, TBODY:/^(.*[\s]+)?removable_rows([\s]+.*)?$/im};
    var prnt = refElm.parentNode;

    var strictTest;
    if(nonstrict) {
        strictTest = function(param){return false};
    } else {
        switch(elmType) {
            case 'TBODY':
            strictTest = function(param){return !param.className.match(/^(.*[\s]+)?removable_rows([\s]+.*)?$/im);};
            break;
            case 'TR':
            strictTest = function(param){return !param.className.match(/^(.*[\s]+)?removable([\s]+.*)?$/im);};
            break;
            default:
            strictTest = function(param){return true};
            break;
        }
    }

    while((prnt.tagName != elmType) || ((prnt.tagName == elmType) && strictTest(prnt))) {
        prnt = prnt.parentNode;
        if(prnt.tagName == 'BODY')
            return false;
    }
    return prnt;
}

function getSpecialSibling(refElm, elmType, direction, nonstrict) {
    elmType = elmType.toUpperCase();
    spRegs = {TR:/^(.*[\s]+)?removable([\s]+.*)?$/im, TBODY:/^(.*[\s]+)?removable_rows([\s]+.*)?$/im};

    var sbl;
    switch(direction) {
        case 'next':
        sbl = refElm.nextSibling;
        break;
        case 'previous':
        default:
        sbl = refElm.previousSibling;
        break;
    }

    var strictTest;
    if(nonstrict) {
        strictTest = function(param){return false};
    } else {
        switch(elmType) {
            case 'TBODY':
            strictTest = function(param){return !param.className.match(/^(.*[\s]+)?removable_rows([\s]+.*)?$/im);};
            break;
            case 'TR':
            strictTest = function(param){return !param.className.match(/^(.*[\s]+)?removable([\s]+.*)?$/im);};
            break;
            default:
            strictTest = function(param){return true};
            break;
        }
    }

    while((sbl && (sbl.nodeType != 1)) || (sbl && (sbl.nodeType != 1) && strictTest(sbl))) {
        switch(direction) {
            case 'next':
            sbl = sbl.nextSibling;
            break;
            case 'previous':
            default:
            sbl = sbl.previousSibling;
            break;
        }
        if(sbl.tagName == 'BODY')
            return false;
    }
    return sbl;
}

////////////////////////////////////////////////////////////////////////////////////
var processNamesByTag = ['SELECT', 'INPUT', 'TEXTAREA'];

function addHypRow(elmClicked, elmsVar) {
    if(!isDOMNode(elmClicked))
        return;

    var row = getSpecialParent(elmClicked, 'TR', true);
    var tbody = getSpecialParent(row, 'TBODY');
    var table = getSpecialParent(tbody, 'TABLE');

	var toAddElm = hypBuildHTML(window[elmsVar][0], null, elmClicked);

    tbody.insertBefore(toAddElm, row);
}

function removeHypRow(elmClicked) {
    if(!isDOMNode(elmClicked))
        return;

    var row = getSpecialParent(elmClicked, 'TR');
    var tbody = getSpecialParent(row, 'TBODY');

	var tmp_rows = getChildElements(tbody);
	var rows = [];
	for(var i=0; i<tmp_rows.length; i++)
		if(tmp_rows[i].className.match(/^(.*[\s]+)?removable([\s]+.*)?$/im)) {
			rows[rows.length] = tmp_rows[i];
		}

    var nameRegEx = new RegExp(/^(.*\[)([0-9]+)(\].*)$/mi);

	for(var i=0, ilen=rows.length; i<ilen; i++) {
        var ar_tbs = rows[i].getElementsByTagName('TBODY');
        for(var k=0, klen=ar_tbs.length; j<klen; k++) {
            if(ar_tbs[k].className.match(/^(.*[\s]+)?removable_rows([\s]+.*)?$/im))
                ar_tbs[k].id = buildTbodyId(ar_tbs[k]);
        }
	    for(var j=0, jlen=processNamesByTag.length; j<jlen; j++) {
    		var ar_elms = rows[i].getElementsByTagName(processNamesByTag[j]);
    		for(var k=0, klen=ar_elms.length; j<klen; j++) {
                var elmRow = ar_elms[k];
    			ar_elms[k].name = buildElementName(ar_elms[k]);
    		}
        }
	}
}

function HypOrderUp(elmClicked) {
    if(!isDOMNode(elmClicked))
        return;

    var row = getSpecialParent(elmClicked, 'TR');
    var tbody = getSpecialParent(row, 'TBODY');
    var prrow = getSpecialSibling(row, 'TR', 'previous');

	if(!isDOMNode(prrow))
		return;

    tbody.insertBefore(row, prrow);

	var tmp_rows = tbody.getElementsByTagName('TR');
	var rows = [];
	for(var i=0; i<tmp_rows.length; i++)
		if(tmp_rows[i].className.match(/^(.*[\s]+)?removable([\s]+.*)?$/im)) {
			rows[rows.length] = tmp_rows[i];
		}

    var nameRegEx = new RegExp(/^(.*\[)([0-9]+)(\].*)$/mi);

	for(var i=0, ilen=rows.length; i<ilen; i++) {
        var ar_tbs = rows[i].getElementsByTagName('TBODY');
        for(var k=0, klen=ar_tbs.length; j<klen; k++) {
            if(ar_tbs[k].className.match(/^(.*[\s]+)?removable_rows([\s]+.*)?$/im))
                ar_tbs[k].id = buildTbodyId(ar_tbs[k]);
        }
	    for(var j=0, jlen=processNamesByTag.length; j<jlen; j++) {
    		var ar_elms = rows[i].getElementsByTagName(processNamesByTag[j]);
    		for(var k=0, klen=ar_elms.length; k<klen; k++) {
                var elmRow = ar_elms[k];
    			ar_elms[k].name = buildElementName(ar_elms[k]);
    		}
        }
	}
}

function HypOrderDown(elmClicked) {
    if(!isDOMNode(elmClicked))
        return;

    var row = getSpecialParent(elmClicked, 'TR');
    var tbody = getSpecialParent(row, 'TBODY');
    var nxrow = getSpecialSibling(row, 'TR', 'next');

	if(!isDOMNode(nxrow))
		return;

    tbody.insertBefore(nxrow, row);

	var tmp_rows = tbody.getElementsByTagName('TR');
	var rows = [];
	for(var i=0; i<tmp_rows.length; i++)
		if(tmp_rows[i].className.match(/^(.*[\s]+)?removable([\s]+.*)?$/im)) {
			rows[rows.length] = tmp_rows[i];
		}

    var nameRegEx = new RegExp(/^(.*\[)([0-9]+)(\].*)$/mi);

	for(var i=0, ilen=rows.length; i<ilen; i++) {
        var ar_tbs = rows[i].getElementsByTagName('TBODY');
        for(var k=0, klen=ar_tbs.length; k<klen; k++) {
            if(ar_tbs[k].className.match(/^(.*[\s]+)?removable_rows([\s]+.*)?$/im))
                ar_tbs[k].id = buildTbodyId(ar_tbs[k]);
        }
	    for(var j=0, jlen=processNamesByTag.length; j<jlen; j++) {
    		var ar_elms = rows[i].getElementsByTagName(processNamesByTag[j]);
    		for(var k=0, klen=ar_elms.length; k<klen; k++) {
                var elmRow = ar_elms[k];
    			ar_elms[k].name = buildElementName(ar_elms[k]);
    		}
        }
	}
}

function keyPressedHasCode(e, code) {
	if (window.event) e = window.event;
	var elm;
	if (e.target) elm = e.target;
	else if (e.srcElement) elm = e.srcElement;
	if (elm.nodeType == 3)
		elm = elm.parentNode;

    return ((e.keyCode && e.keyCode==code) || (e.which && e.which==code));
}

var nwtousRegExp = new RegExp('\\W', 'g');
function queryToAnchorName(val, mfier) {
    return val.replace(nwtousRegExp, '_')+'_'+mfier+'_anchor';
}

function highlight_search_result(val, mfier) {
    val = val.toLowerCase();
    var noHashHref = location.href.replace(new RegExp('#.*'), '');
    var newHash = '#'+queryToAnchorName(val, mfier);
    location.href = noHashHref+newHash;

    var theElm;
    if(theElm = document.getElementsByName(queryToAnchorName(val, mfier))[0]) {
        var greatGrandParent = theElm.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
        addClass(greatGrandParent, 'hypFound');
    }
}
 */

if (!function_exists('apache_request_headers')) {
	function apache_request_headers() {
		$arh = array();
		$rx_http = '/\AHTTP_/';
		foreach ($_SERVER as $key => $val) {
			if (preg_match($rx_http, $key)) {
				$arh_key = preg_replace($rx_http, '', $key);
				$rx_matches = array();
				// do some nasty string manipulations to restore the original letter case
				// this should work in most cases
				$rx_matches = explode('_', strtolower($arh_key));
				if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
					foreach ($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
					$arh_key = implode('-', $rx_matches);
				}
				$arh[$arh_key] = $val;
			}
		}
		if (isset($_SERVER['CONTENT_TYPE'])) $arh['Content-Type'] = $_SERVER['CONTENT_TYPE'];
		if (isset($_SERVER['CONTENT_LENGTH'])) $arh['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
		return ($arh);
	}
}

if (!function_exists('apache_response_headers')) {
	function apache_response_headers() {
		$arh = array();
		$headers = headers_list();
		foreach ($headers as $header) {
			$header = explode(":", $header);
			$arh[array_shift($header)] = trim(implode(":", $header));
		}
		return $arh;
	}
}

if(!function_exists('array_column')) {
	function array_column($input = null, $columnKey = null, $reindex=false, $indexKey = null)
	{
		// Using func_get_args() in order to check for proper number of
		// parameters and trigger errors exactly as the built-in array_column()
		// does in PHP 5.5.
		$argc = func_num_args();
		$params = func_get_args();

		if ($argc < 2) {
			trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
			return null;
		}

		if (!is_array($params[0])) {
			trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
			return null;
		}

		if (!is_int($params[1])
			&& !is_float($params[1])
			&& !is_string($params[1])
			&& $params[1] !== null
			&& !(is_object($params[1]) && method_exists($params[1], '__toString'))
		) {
			trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
			return false;
		}

		if (isset($params[2])
			&& !is_int($params[2])
			&& !is_float($params[2])
			&& !is_string($params[2])
			&& !(is_object($params[2]) && method_exists($params[2], '__toString'))
		) {
			trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
			return false;
		}

		$paramsInput = $params[0];
		$paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

		$paramsIndexKey = null;
		if (isset($params[2])) {
			if (is_float($params[2]) || is_int($params[2])) {
				$paramsIndexKey = (int) $params[2];
			} else {
				$paramsIndexKey = (string) $params[2];
			}
		}

		$resultArray = array();

		foreach ($paramsInput as $rk=>$row) {

			$key = $value = null;
			$keySet = $valueSet = false;

			if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
				$keySet = true;
				$key = (string) $row[$paramsIndexKey];
			}

			if ($paramsColumnKey === null) {
				$valueSet = true;
				$value = $row;
			} elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
				$valueSet = true;
				$value = $row[$paramsColumnKey];
			}

			if ($valueSet) {
				if ($reindex && $keySet) {
					$resultArray[$key] = $value;
				} else if($reindex) {
					$resultArray[] = $value;
				} else {
			$resultArray[$rk] = $value;
		}
			}

		}

		return $resultArray;
	}
}

/*
if(!function_exists('array_insert')) {
function array_insert (&$array, $position, $insert_array) {
  $first_array = array_splice ($array, 0, $position);
  $array = array_merge ($first_array, $insert_array, $array);
}
}
*/

if(!function_exists('array_insert')) {
	function array_rearrange_key ($array, $key, $position) {
		$srow = array($array[$key]);
		$values = array_values($array);
		$keys = array_keys($array);
		$skey = array_search($key, $keys);
		unset($values[$skey]);
		array_splice($values, $position, 0, $srow);
		unset($keys[$skey]);
		array_splice($keys, $position, 0, $key);
		return array_combine($keys, $values);
	}
}

if(!function_exists('scandir')) {
	function scandir($dir) {
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
			$files[] = $filename;
		}
		return $files;
	}
}

if (!function_exists('file_put_contents')) {
	function file_put_contents($filename, $data) {
		$f = @fopen($filename, 'w');
		if (!$f) {
			return false;
		} else {
			$bytes = fwrite($f, $data);
			fclose($f);
			return $bytes;
		}
	}
}

if (!function_exists('json_encode')) {
	function json_encode($a=false) {
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		if (is_scalar($a)) {
			if (is_float($a)) {
				// Always use "." for floats.
				return floatval(str_replace(",", ".", strval($a)));
			}
		
			if (is_string($a)) {
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			} else
				return $a;
		}
		$isList = true;
		for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
			if (key($a) !== $i) {
				$isList = false;
				break;
			}
		}
		$result = array();
		if ($isList) {
			foreach ($a as $v) $result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		} else {
			foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}

if (!function_exists('array_column')) {
	function array_column($array, $column){
		return array_map(function ($v){ return $v[$column]; }, $array);
	}
}

function get_font_image_path2($path, $image_name) {
	$filename = explode('.',$image_name);
	array_pop($filename);
	$filename = implode('.',$filename);
	
	if(file_exists($path.DIRECTORY_SEPARATOR.$filename.'.jpg'))
		return $filename.'.jpg';
	else if(file_exists($path.DIRECTORY_SEPARATOR.$filename.'.png'))
		return $filename.'.png';
		
	else if(file_exists($path.DIRECTORY_SEPARATOR.str_replace('_', ' ', $filename).'.jpg'))
		return str_replace('_', '%20', $filename).'.jpg';
	else if(file_exists($path.DIRECTORY_SEPARATOR.str_replace('_', ' ', $filename).'.png'))
		return str_replace('_', '%20', $filename).'.png';
		
	else
		return false;
}

function encode_string($str,$ky=''){
	if($ky=='')
		return $str;
		
	$ky=str_replace(chr(32),'',$ky);
	
	if(strlen($ky)<8)
		exit('key error');
		
	$kl=strlen($ky)<32?strlen($ky):32;
	
	$k=array();
	for($i=0;$i<$kl;$i++) {
		$k[$i]=ord($ky{$i})&0x1F;
	}
	$j=0;
	for($i=0;$i<strlen($str);$i++) {
		$e=ord($str{$i});
		$str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);
		$j++;
		$j=$j==$kl?0:$j;
	}
	return $str;
}

function generateRandomString($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+=\\|/?.>,<;:\'\"') {
	
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[mt_rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function dir_get_files($dir, $ext='') {
	$files = scandir($dir);
	if(!empty($ext)) {
		$ext = explode(',',$ext);
	}
	for($i=0;$i<count($files);$i++) {
		if(is_dir($dir.DIRECTORY_SEPARATOR.$files[$i])) {
			array_splice($files, $i, 1);
			$i--;
		} else if(!empty($ext)) {
			$fparts = explode('.', $files[$i]);
			if((count($fparts)<2) || (!in_array($fparts[count($fparts)-1], $ext))) {
				array_splice($files, $i, 1);
				$i--;
			}
		}
	}
	if(!is_array($files))
		$files = array();
		
	return $files;
}

function processRelatedTablesData($moduleid) {
	global $table_relations;
	
	$db = &tpt_Database::getInstance();
	
	$tablesArr = array();
	
	$tmp_tables = $table_relations[$moduleid];
	foreach($tmp_tables as $key=>$table) {
		if(!isset($tablesArr[$table['related_as']]))
			$tablesArr[$table['related_as']] = array();
		
		
			
		$q = 'SHOW COLUMNS FROM `' . $table['table_name'] . '`';
		$db->query($q);
		$fields = $db->fetch_assoc_list();
		
		$tablesArr[$table['related_as']][$table['table_name']] = $table;
		$tablesArr[$table['related_as']][$table['table_name']]['table_order'] = $key;
		
		$tablesArr[$table['related_as']][$table['table_name']]['matching_fields'] = tpt_prm::parse($tablesArr[$table['related_as']][$table['table_name']]['matching_fields'], 'array:pairs', array());
		
		$tablesArr[$table['related_as']][$table['table_name']]['allowed_fields'] = tpt_prm::parse($tablesArr[$table['related_as']][$table['table_name']]['allowed_fields'], 'array', array());
		$tablesArr[$table['related_as']][$table['table_name']]['disallowed_fields'] = tpt_prm::parse($tablesArr[$table['related_as']][$table['table_name']]['disallowed_fields'], 'array', array());
		
		$tablesArr[$table['related_as']][$table['table_name']]['fieldcontrols'] = tpt_prm::parse($tablesArr[$table['related_as']][$table['table_name']]['field_controls'], 'multiarray:pairs', array());
		$tablesArr[$table['related_as']][$table['table_name']]['fields'] = array();
		foreach($fields as $fld) {
			$field = $fld['Field'];
			
			$allowed = true;
			if(!empty($tablesArr[$table['related_as']][$table['table_name']]['allowed_fields'])) {
				if(!in_array($field, $tablesArr[$table['related_as']][$table['table_name']]['allowed_fields'])) {
					$allowed = false;
				}
			}
			
			if(!empty($tablesArr[$table['related_as']][$table['table_name']]['disallowed_fields'])) {
				if(in_array($field, $tablesArr[$table['related_as']][$table['table_name']]['disallowed_fields'])) {
					$allowed = false;
				}
			}
			
			if($allowed) {
				if(isset($tablesArr[$table['related_as']][$table['table_name']]['field_controls'][$field]))
					$tablesArr[$table['related_as']][$table['table_name']]['fields'][] = array('field'=>$field, 'control'=>$tablesArr[$table['related_as']][$table['table_name']]['field_controls'][$field]);
				else
					$tablesArr[$table['related_as']][$table['table_name']]['fields'][] = array('field'=>$field, 'control'=>$tablesArr[$table['related_as']][$table['table_name']]['default_field_control']);
			}
			
		}
	}
	
	return $tablesArr;
}

function arrayDouble($str='') {
	return array(strval($str), strval($str));
}

function rgb2hex2rgb($c){
   if(!$c) return false;
   $c = trim($c);
   $out = false;
  if(preg_match("/^[0-9ABCDEFabcdef\#]+$/i", $c)){
	  $c = str_replace('#','', $c);
	  $l = strlen($c) == 3 ? 1 : (strlen($c) == 6 ? 2 : false);

	  if($l){
		 unset($out);
		 $out[0] = $out['r'] = $out['red'] = hexdec(substr($c, 0,1*$l));
		 $out[1] = $out['g'] = $out['green'] = hexdec(substr($c, 1*$l,1*$l));
		 $out[2] = $out['b'] = $out['blue'] = hexdec(substr($c, 2*$l,1*$l));
	  }else $out = false;
			 
   }elseif (preg_match("/^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$/i", $c)){
	  $spr = str_replace(array(',',' ','.'), ':', $c);
	  $e = explode(":", $spr);
	  if(count($e) != 3) return false;
		 $out = '#';
		 for($i = 0; $i<3; $i++)
			$e[$i] = dechex(($e[$i] <= 0)?0:(($e[$i] >= 255)?255:$e[$i]));
			 
		 for($i = 0; $i<3; $i++)
			$out .= ((strlen($e[$i]) < 2)?'0':'').$e[$i];
				 
		 $out = strtoupper($out);
   }else $out = false;
		 
   return $out;
}

function inverseHex( $color )
{
	 $color       = trim($color);
	 $prependHash = false;
 
	 if(strpos($color,'#')!==false) {
		  $prependHash = true;
		  $color       = str_replace('#',NULL,$color);
	 }
 
	 switch($len=strlen($color)) {
		  case 3:
			   $color=preg_replace("/(.)(.)(.)/","\\1\\1\\2\\2\\3\\3",$color);
		  case 6:
			   break;
		  default:
			   //trigger_error("Invalid hex length ($len). Must be (3) or (6)", E_USER_ERROR);
	 }
 
	 IF(!preg_match('/[a-f0-9]{6}/i',$color)) {
		  $color = htmlentities($color);
		  //trigger_error( "Invalid hex string #$color", E_USER_ERROR );
	 }
 
	 $r = dechex(255-hexdec(substr($color,0,2)));
	 $r = (strlen($r)>1)?$r:'0'.$r;
	 $g = dechex(255-hexdec(substr($color,2,2)));
	 $g = (strlen($g)>1)?$g:'0'.$g;
	 $b = dechex(255-hexdec(substr($color,4,2)));
	 $b = (strlen($b)>1)?$b:'0'.$b;
 
	 return ($prependHash?'#':null).$r.$g.$b;
}


function process_fields(&$vars, $fields, $usable_controls) {
	foreach($fields as $rf) {
		//var_dump($_POST[$rf['name']]);
		if(in_array($rf['control'], $usable_controls)) {
		if($rf['control'] == 'p') {
			
		} else {
			$vars['template_data']['form_values'][$rf['name']] = (isset($_POST[$rf['name']])?$_POST[$rf['name']]:'');
		}
		if($rf['control'] == 'rg') {
			if($rf['required'] && !isset($_POST[$rf['name']])) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields'][$rf['name']] = 1;
			}
		} else {
			if($rf['required'] && empty($_POST[$rf['name']])) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields'][$rf['name']] = 1;
			}
			if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
			$vars['template_data']['valid_form'] = false;
			$vars['template_data']['invalid_fields'][$rf['name']] = 1;
			}
		}
		if($rf['store_field']) {
			$field_value = '';
			if(strtolower($rf['control']) == 'p') {
			$field_value = '"'.sha1((isset($_POST[$rf['name']])?$_POST[$rf['name']]:'')).'"';
			} else if(strtolower($rf['control']) == 't') {
			$field_value = '"'.mysql_real_escape_string((isset($_POST[$rf['name']])?$_POST[$rf['name']]:'')).'"';
			} else if(strtolower($rf['control']) == 'stsel') {
			$field_value = '"'.mysql_real_escape_string((isset($_POST[$rf['name']])?$_POST[$rf['name']]:'')).'"';
			} else {
			$field_value = intval((isset($_POST[$rf['name']])?$_POST[$rf['name']]:0), 10);
			}
			$vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
		}
		}
	}
}
// Demo
// echo inverseHex('#000000'); // #ffffff


function format_price($value) {
	return '&#36;'.number_format($value, 2);
}

function format_price_array(&$item, $index) {
	//var_dump($index);//die();
	//var_dump($item);//die();
	$item = '&#36;'.number_format($item, 2);
}

function bytesToSize($bytes, $precision = 2)
{
	$kilobyte = 1024;
	$megabyte = $kilobyte * 1024;
	$gigabyte = $megabyte * 1024;
	$terabyte = $gigabyte * 1024;

	if (($bytes >= 0) && ($bytes < $kilobyte)) {
		return $bytes . ' B';

	} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
		return round($bytes / $kilobyte, $precision) . ' KB';

	} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
		return round($bytes / $megabyte, $precision) . ' MB';

	} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
		return round($bytes / $gigabyte, $precision) . ' GB';

	} elseif ($bytes >= $terabyte) {
		return round($bytes / $terabyte, $precision) . ' TB';
	} else {
		return $bytes . ' B';
	}
}

function tpt_die() {
	global $tpt_vars;
	if(isDump()) {
		$bck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		echo 'tpt_die(): '.$bck[0]['file'].' ('.$bck[0]['line'].'):'.'<br />';

		if(isDev('tpt_die_backtrace') && (!empty($backtrace) || !empty($_GET['tpt_die_backtrace']))) {
			//var_dump(isDev('tpt_die_backtrace'));die();
			echo '<pre>'."\n";
			debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			echo '</pre>'."\n";
			//echo 'tpt_die(): '.$bck[0]['file'].' ('.$bck[0]['line'].'):'.'<br />';
			//die();
		}

		die();
	}
}
function tpt_dump($var, $die=false, $visible_to=null, $backtrace=false, $file='', $line='', $pre=false) {
	global $tpt_vars;

	$v = '';
	if((!empty($visible_to) && !empty($tpt_vars['config']['var_dump_users_ips'][$visible_to]) && ($tpt_vars['user']['client_ip'] === $tpt_vars['config']['var_dump_users_ips'][$visible_to]))) {
		$v = ' '.$visible_to.':';
	}
	if((!empty($v)) || (empty($visible_to) && isDump())) {
		if(!empty($pre)) {
			echo '<pre>'."\n";
		}

		$bck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		echo 'tpt_dump():'.$v.' '.$bck[0]['file'].' ('.$bck[0]['line'].'):'.'<br />';
		if(isDev('tpt_dump_backtrace') && (!empty($backtrace) || !empty($_GET['tpt_dump_backtrace']))) {
			//var_dump(isDev('tpt_dump_backtrace'));die();
			echo '<pre>'."\n";
			debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			echo '</pre>'."\n";
			//echo 'tpt_dump(): '.$bck[0]['file'].' ('.$bck[0]['line'].'):'.'<br />';
			//die();
		} else if(!empty($file)) {
			echo '-------------------------------------'."\n";
			echo $file."\n";
			if(!empty($line)) {
				echo $line;
			}
			echo "\n";
		}
		var_dump($var);
		if(!empty($file)) {
			echo '-------------------------------------'."\n";
		}
		if(!empty($pre)) {
			echo '</pre>'."\n";
		}
		echo '<br />'."\n";
		if($die) {
			die();
		}
	}

}

function isDump() {
	global $tpt_vars;
	if(in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['var_dump_ips'])) {
		return true;
	}
	return false;
}

function isDev($index=null) {
	global $tpt_vars;

	/*
	if($index == 'newcheckout') {
		tpt_dump($tpt_vars['user']['client_ip']);
		tpt_dump($tpt_vars['config']['devtest_ips'][$index], true);
	}
	*/
	if(!empty($tpt_vars['config']['devtest_ips'][$index]) && is_array($tpt_vars['config']['devtest_ips'][$index]) && in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['devtest_ips'][$index])) {
		return true;
	}
	return false;
}

function isDevLog() {
	global $tpt_vars;
	if(!empty($tpt_vars['config']['devlog_ips']) && is_array($tpt_vars['config']['devlog_ips']) && in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['devlog_ips'])) {
		return true;
	}
	return false;
}

function isDevAccess() {
	global $tpt_vars;
	if(!empty($tpt_vars['config']['devaccess_ips']) && is_array($tpt_vars['config']['devaccess_ips']) && in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['devaccess_ips'])) {
		return true;
	}
	return false;
}

function isUltraUser() {
	global $tpt_vars;
	if(in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['ultra_devtest_ips'])) {
		return true;
	}
	return false;
}

function getRGBLuminance($red, $green, $blue) {
	return round((0.2126*$red) + (0.7152*$green) + (0.0722*$blue), 5);
}

function get_number_sign_char($number, $zeroasplus=false) {
	if($number > 0) {
		return '+';
	} else if($number < 0) {
		return '-';
	} else {
		if($zeroasplus) {
			return '+';
		} else {
			return '';
		}
	}
}


function normalize_filename($filename, $extension, $keeppath = false) {
	if(empty($filename)) {
		return false;
	}
	
	$filename = explode(DIRECTORY_SEPARATOR, $filename);
	$tfn = trim($filename[count($filename)-1]);
	if(empty($tfn)) {
		$filename[count($filename)-1] = $extension.'_'.DEFAULT_FILENAME.'_'.time().$extension;
	} else {
		$filename[count($filename)-1] = explode('.', $tfn);
		if((count($filename[count($filename)-1]) == 1) || ($filename[count($filename)-1][count($filename[count($filename)-1])-1] != $extension)) {
			//tpt_dump($filename, true);
			array_push($filename[count($filename)-1], $extension);
		}
		$filename[count($filename)-1] = implode('.', $filename[count($filename)-1]);
	}
	
	if($keeppath) {
		$filename = implode(DIRECTORY_SEPARATOR, $filename);
	} else {
		$filename = $filename[count($filename)-1];
	}
	
	return $filename;
}



function sendSingleRequest($url, $body='', $method='g', $headers=null, $options=array()) {
	global $tpt_vars;
	
	$curl = curl_init();
	
	//die(strstr($url, 'https://'));
	//die($url);
	//die($body);
	//var_dump($url);
	//var_dump($body);
	if(strpos($url, 'https://') === 0) {
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		//die('asdasdasdasdasdas');
	}
	
	$cookiefile = $tpt_vars['config']['curl']['sendSingleRequest']['cookie_dir'].DIRECTORY_SEPARATOR.((isset($options['cookie_source'])&&is_string($options['cookie_source'])&&(strlen($options['cookie_source'])>0))?$options['cookie_source']:$tpt_vars['config']['curl']['sendSingleRequest']['cookie_source']);
	$cookiejar  = $tpt_vars['config']['curl']['sendSingleRequest']['cookie_dir'].DIRECTORY_SEPARATOR.((isset($options['cookie_destination'])&&is_string($options['cookie_destination'])&&(strlen($options['cookie_destination'])>0))?$options['cookie_destination']:$tpt_vars['config']['curl']['sendSingleRequest']['cookie_source']);
	$output_file_name = $tpt_vars['config']['curl']['sendSingleRequest']['response_file_dir'].DIRECTORY_SEPARATOR.((isset($options['output_file_name'])&&is_string($options['output_file_name'])&&(strlen($options['output_file_name'])>0))?$options['output_file_name']:$tpt_vars['config']['curl']['sendSingleRequest']['response_file_name']);
	$tmpfile = $tpt_vars['config']['curl']['sendSingleRequest']['downloads_dir'].DIRECTORY_SEPARATOR.'tmpfile'.sha1(time().date('u').mt_rand(0,65535));

	$tmp = null;
	if($method == 'd') {
		//curl_setopt($curl, CURLOPT_BUFFERSIZE,64000);
		//curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, 'outprogress');
		$tmp = fopen($tmpfile, 'w');
		curl_setopt($curl, CURLOPT_FILE, $tmp);
	} else {
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	}
	
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	//if(is_file($cookiefile)) {
		if(isset($options['cookie_truncate']) && !empty($options['cookie_truncate'])) {
			file_put_contents($cookiefile, '');
		}
		if(isset($options['cookie_append']) && !empty($options['cookie_append'])) {
			file_put_contents($cookiefile, $options['cookie_append'], FILE_APPEND);
		}
		if(isset($options['cookie_send']) && !empty($options['cookie_send'])) {
			curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiefile);
		}
		if(isset($options['cookie_save']) && !empty($options['cookie_save'])) {
			curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiejar);
		}
	//}
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	if(($method == 'p') || (strtolower($method) == 'post')) {
		if(is_array($body)) {
			$body = http_build_query($body);
		}
		$rtext  = <<< EOT
$body
EOT;
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $rtext);
		$headers = array('Content-Length: '.strlen($rtext));
		
		//$headers[] = 'X-Requested-With: XMLHttpRequest';
		//$headers[] = 'X-Prototype-Version: 1.6.1';
	}
	//foreach($headers as $header=>$data) {
	//	$hdr[] = $header.': '.$data;
	//}
	if(!empty($headers)) {
		$hdrs = array();
		if (is_array($headers)) {
			foreach($headers as $hdr) {
				$h = explode(': ', $hdr, 2);
				if(!empty($h[0]) && isset($h[1])) {
					$hdrs[] = $h[0].': '.$h[1];
				}
			}
		} else if(is_string($headers)) {
			$headers = preg_split('#\R+#', trim($headers));
			foreach($headers as $hdr) {
				$h = explode(': ', $hdr, 2);
				if(!empty($h[0]) && isset($h[1])) {
					$hdrs[] = $h[0].': '.$h[1];
				}
			}
		}
		if(!empty($hdrs)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $hdrs);
		}
	}
	//$headers[] = 'Content-Type: '.;
	curl_setopt($curl, CURLOPT_URL, $url);
	//die($rtext);
	$xxx = curl_exec($curl);
	//echo $xxx;die();
	$info = curl_getinfo($curl);
	$errno = curl_errno($curl);
	$error = curl_error($curl);
	curl_close($curl);

	if ($method == 'd') {
		fclose($tmp);
	}
	if(empty($errno)) {
		//if (is_file($output_file_name)) {
			if ($method == 'd') {
				$output_file_name = $tpt_vars['config']['curl']['sendSingleRequest']['downloads_dir'].DIRECTORY_SEPARATOR.((isset($options['output_file_name']) && is_string($options['output_file_name']) && (strlen($options['output_file_name']) > 0)) ? $options['output_file_name'] : basename($info['url']));
				file_put_contents($output_file_name, ((isset($options['output_prefix']))?$options['output_prefix']:'').file_get_contents($tmpfile), ((isset($options['output_append'])&&!empty($options['output_append']))?FILE_APPEND:null));
				unlink($tmpfile);
				$xxx = 'File download complete: ' . $output_file_name;
			} else {
				if(isset($options['output_save'])&&!empty($options['output_save'])) {
					file_put_contents($output_file_name, ((isset($options['output_prefix']))?$options['output_prefix']:'').$xxx, ((isset($options['output_append']) && !empty($options['output_append'])) ? FILE_APPEND : null));
				}
			}
		//}
	} else {
		$xxx = false;
	}

	$htmlinfo = var_export($info, true);

	$response = array(
		'rawinfo'=>$info,
		'info'=>$htmlinfo,
		'body'=>$xxx,
		'errno'=>$errno,
		'error'=>$error
	);
	

	if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_curl'])) {
		$postdata = serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
		//die($query);
		tpt_logger::log_curl($tpt_vars, 'tpt_request_rq_curl', $body, $method, ((isset($options['cookie_truncate'])&&!empty($options['cookie_truncate']))?$options['cookie_truncate']:null), var_export($headers, true), $htmlinfo, $xxx, $errno, $error);
		

	}
	if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_curl_dev'])) {
		//$postdata = serialize(debug_backtrace());
		tpt_logger::log_curl($tpt_vars, 'tpt_request_rq_curl_dev', $body, $method, ((isset($options['cookie_truncate'])&&!empty($options['cookie_truncate']))?$options['cookie_truncate']:null), var_export($headers, true), $htmlinfo, $xxx, $errno, $error);
	}
	
	
	return $response;

}


function floordec($value,$decimals=2){   
	 return floor($value*pow(10,$decimals))/pow(10,$decimals);
}



/*
function ueval($arg = array('value'), $value) {
	$fvalue = null;
	switch(true) {
		case is_string($arg) :
			$arg = explode('|', $arg);
		case is_array($arg) :
		default :
			foreach($arg as $exp) {
				preg_match('#[a-zA-Z_]+\((\'.*?\'|".*?"|.*?)\)#', $exp, $mtch);
			}
	}
}
*/


function getModule(&$vars, $moduleName) {
	if(!empty($vars['modules']['handler']->modules[$moduleName])) {
		return $vars['modules']['handler']->modules[$moduleName];
	}


	return $vars['modules']['handler']->getModule($vars, $moduleName);
}



function tpt_parse_url2($url, $buildable=false) {
	if(is_string($url)) {
		$purl = array();
		$purl['preprefix'] = '';
		$purl['scheme'] = '';
		$purl['scheme_separator'] = '';
		$purl['relative_scheme'] = 0;
		$purl['fragment'] = '';
		$purl['query'] = '';
		$purl['authorization'] = '';
		$purl['host'] = '';
		$purl['path'] = '';
		$purl['assumed_path'] = '';
		$u = $url;
		$i = 0;
		do{
			$u = urldecode($u);
			$i++;
			if($i>=POTENTIAL_ENDLESS_LOOP_MAXIMUM_COUNTER) {
				global $tpt_vars;
				if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_endless_loops'])) {
					tpt_logger::log_endless_loop($tpt_vars, 'tpt_request_rq_endless_loops', $i."\n".$u, __FILE__, __LINE__);
				}
				//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log_query_errors_dev']));
				//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log']));
				//tpt_dump(isDevLog());
				//tpt_dump($unlog, true);

				if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_endless_loops_dev'])) {

					//tpt_dump($query, true);
					tpt_logger::log_endless_loop($tpt_vars, 'tpt_request_rq_endless_loops_dev', $i."\n".$u, __FILE__, __LINE__);
				}

				break;
			}
		} while((preg_match('#%[0-9a-f]{2}#', $u) && ($i<POTENTIAL_ENDLESS_LOOP_MAXIMUM_COUNTER)));

		preg_match('#^(\s*url:\s*)?(.*)?$#i', $u, $m);
		$preprefix = $m[1];
		$step1 = $m[2];
		if(!empty($preprefix)) {
			$purl['preprefix'] = $preprefix;
		}
		if(!empty($step1)) {
			//$u = preg_replace('#(\s*url:\s*)?(.*)?#i', '$2', $u);
			preg_match('#^(?:(https?|s?ftp|gohper|mailto|mid|cid|news|nntp|prospero|telnet|rlogin|tn3270|wais|javascript):)?(.*)?$#i', $step1, $m);
			$scheme = $m[1];
			if (!empty($scheme)) {
				$purl['scheme'] = $scheme;
			}
			$step2 = $m[2];
			switch ($scheme) {
				case 'gopher':
					break;
				case 'mailto':
					break;
				case 'mid':
					break;
				case 'cid':
					break;
				case 'news':
					break;
				case 'nntp':
					break;
				case 'prospero':
					break;
				case 'telnet':
					break;
				case 'rlogin':
					break;
				case 'tn3270':
					break;
				case 'wais':
					break;
				case 'javascript':
					break;
				case 'ftp':
				case 'sftp':
				case 'http':
				case 'https':
				default:
					if (!empty($step2)) {
						preg_match('#^(//)?(.*)?$#', $step2, $m);
						$scheme_separator = $m[1];
						$purl['scheme_separator'] = ':';
						if (!empty($scheme_separator)) {
							$purl['scheme_separator'] = ':'.$scheme_separator;
						}
						if (!empty($scheme_separator) && empty($scheme)) {
							$purl['relative_scheme'] = 1;
						}
						$step3 = $m[2];
						if (!empty($step3)) {
							preg_match('#(.*?)(?:\#(\S*))?$#', $step3, $m);
							$fragment = (!empty($m[2])?$m[2]:'');
							if (!empty($fragment)) {
								$purl['fragment'] = $fragment;
							}
							$step4 = $m[1];
							if (!empty($step4)) {
								//tpt_dump($step4);
								preg_match('#([^\?]*)?(?:\?(.*))?$#', $step4, $m);
								//tpt_dump($m);
								$query = (!empty($m[2])?$m[2]:'');
								if (!empty($query)) {
									$purl['query'] = $query;
								}

								//tpt_dump($url);
								//tpt_dump($purl, true);
								$step5 = $m[1];
								if (!empty($step5)) {
									preg_match('#(([^@]*)@)?(.*)?$#', $step5, $m);
									$authorization = $m[2];
									if (!empty($authorization)) {
										$purl['authorization'] = $authorization;
									}
									$step6 = $m[3];
									//tpt_dump($step6);
									if (!empty($step6)) {
										preg_match('#([^/]*?)(/.*)?$$#', $step6, $m);
										$host = $m[1];
										if (!empty($host)) {
											$purl['host'] = $host;
										}
										$path = ((isset($m[2]) && !empty($m[2])) ? $m[2] : '');
										$assumed_path = $path;
										if (!empty($path)) {
											$purl['path'] = $path;
										} else {
											$assumed_path = '/';
										}
										$purl['assumed_path'] = $assumed_path;
									}
								}
							}
						}
					}
					break;
			}
		}


		/*
		if(empty($url['scheme'])) {
			$url['scheme'] = '';
		} else if(!empty($buildable)) {
			$url['scheme'] = $url['scheme'].'://';
		}

		if(empty($url['host'])) {
			$url['host'] = '';
		}

		if(empty($url['port'])) {
			$url['port'] = '';
		} else if(!empty($buildable)) {
			$url['port'] = ':'.$url['port'];
		}

		if(empty($url['path'])) {
			$url['path'] = '';
		}

		if(empty($url['query'])) {
			$url['query'] = '';
		} else if(!empty($buildable)) {
			$url['query'] = '?'.$url['query'];
		}
		*/
		$url = $purl;
	} else if(is_array($url)) {
		if(empty($url['scheme'])) {
			$url['scheme'] = '';
		} else if(!empty($buildable)) {
			$url['scheme'] = $url['scheme'].'://';
		}

		if(empty($url['host'])) {
			$url['host'] = '';
		}

		if(empty($url['port'])) {
			$url['port'] = '';
		} else if(!empty($buildable)) {
			$url['port'] = ':'.$url['port'];
		}

		if(empty($url['path'])) {
			$url['path'] = '';
		} else if(!empty($buildable) && !preg_match('#^/.*#', $url['path'])) {
			$url['path'] = '/'.$url['path'];
		}

		if(empty($url['query'])) {
			$url['query'] = '';
		} else if(!empty($buildable)) {
			$url['query'] = '?'.$url['query'];
		}
	} else {
		return array(
			'scheme'=>'',
			'host'=>'',
			'port'=>'',
			'path'=>'',
			'query'=>''
		);
	}

	return $url;
}
function tpt_parse_url($url, $buildable=false) {
	if(is_string($url)) {
		$url = parse_url($url);
		if(empty($url['scheme'])) {
			$url['scheme'] = '';
		} else if(!empty($buildable)) {
			$url['scheme'] = $url['scheme'].'://';
		}

		if(empty($url['host'])) {
			$url['host'] = '';
		}

		if(empty($url['port'])) {
			$url['port'] = '';
		} else if(!empty($buildable)) {
			$url['port'] = ':'.$url['port'];
		}

		if(empty($url['path'])) {
			$url['path'] = '';
		}

		if(empty($url['query'])) {
			$url['query'] = '';
		} else if(!empty($buildable)) {
			$url['query'] = '?'.$url['query'];
		}
	} else if(is_array($url)) {
		if(empty($url['scheme'])) {
			$url['scheme'] = '';
		} else if(!empty($buildable)) {
			$url['scheme'] = $url['scheme'].'://';
		}

		if(empty($url['host'])) {
			$url['host'] = '';
		}

		if(empty($url['port'])) {
			$url['port'] = '';
		} else if(!empty($buildable)) {
			$url['port'] = ':'.$url['port'];
		}

		if(empty($url['path'])) {
			$url['path'] = '';
		} else if(!empty($buildable) && !preg_match('#^/.*#', $url['path'])) {
			$url['path'] = '/'.$url['path'];
		}

		if(empty($url['query'])) {
			$url['query'] = '';
		} else if(!empty($buildable)) {
			$url['query'] = '?'.$url['query'];
		}
	} else {
		return array(
			'scheme'=>'',
			'host'=>'',
			'port'=>'',
			'path'=>'',
			'query'=>''
		);
	}

	return $url;
}

function tpt_build_url($components) {
	$url = tpt_parse_url($components, true);

	return $url['scheme'].$url['host'].$url['port'].$url['path'].$url['query'];
}

function remove_url_query_parameter($url, $parameter) {
	$build = 0;
	if(is_string($url)) {
		$build = 1;
	}
	$url = tpt_parse_url($url);

	$q = array();
	parse_str($url['query'], $q);
	if(!is_array($parameter)) {
		$parameter = array($parameter);
	}
	foreach($parameter as $param) {
		unset($q[$param]);
	}

	$url['query'] = http_build_query($q);

	if(!empty($build)) {
		$url = tpt_build_url($url);
	}
	return $url;
}

function file_extension($file, $lowercase=false) {
	if($lowercase) {
		$file=strtolower($file);
	}
	return substr(strrchr($file,'.'), 1);
}
