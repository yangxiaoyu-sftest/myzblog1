<?php
/**
 * Z-Blog with PHP
 * @author 
 * @copyright (C) RainbowSoft Studio
 * @version 2.0 2013-06-14
 */


function CheckRights($action){

	Logs('$action=' . $action);
	
	if ($GLOBALS['zbp']->user->Level > $GLOBALS['actions'][$action]) {
		return false;
	} else {
		return true;
	}	

}

function ViewList($page,$cate,$auth,$date,$tags){

	foreach ($GLOBALS['Filter_Plugin_ViewList_Begin'] as $fpname => &$fpsignal) {
		$fpreturn=$fpname($page,$cate,$auth,$date,$tags);
		if ($fpsignal==PLUGIN_EXITSIGNAL_RETURN) {return $fpreturn;}
	}
	
	global $zbp;

	$zbp->title=$zbp->option['ZC_BLOG_SUBTITLE'];
	$html=null;

	if(isset($zbp->templatetags['TEMPLATE_DEFAULT'])){$html=$zbp->templatetags['TEMPLATE_DEFAULT'];}

	foreach ($zbp->templatetags as $key => $value) {
		$html=str_replace('<#' . $key . '#>', $value, $html);
	}

	echo $html;
	return null;

}

function ViewArticle(){


}

function ViewPage(){


}

function Login(){
	global $zbp;


	if (isset($zbp->membersbyname[GetVars('username')])) {
		$m=$zbp->membersbyname[GetVars('username')];
		if($m->Password == md5(GetVars('password') . $m->Guid)){
			if(GetVars('savedate')==0){
				setcookie("username", GetVars('username'),0,$zbp->cookiespath);
				setcookie("password", GetVars('password'),0,$zbp->cookiespath);
			}else{
				setcookie("username", GetVars('username'), time()+3600*24*GetVars('savedate'),$zbp->cookiespath);
				setcookie("password", GetVars('password'), time()+3600*24*GetVars('savedate'),$zbp->cookiespath);
			}
			header('Location:admin/');
		}else{
			throw new Exception($GLOBALS['lang']['error'][8]);
		}
	}else{
		throw new Exception($GLOBALS['lang']['error'][8]);
		
	}

}


function Logout(){
	global $zbp;
	setcookie("username", "",time() - 3600,$zbp->cookiespath);
	setcookie("password", "",time() - 3600,$zbp->cookiespath);
}

function Reload(){

	$qs=GetVars('QUERY_STRING','SERVER');
	$r=null;

	if(strpos($qs,'statistic')){
		$r='statistic';
		$r .= '<tr><td>1</td><td>a</td><td>2</td><td>b</td></tr>';
		$r .= '<tr><td>1</td><td>a</td><td>2</td><td>b</td></tr>';
		$r .= '<tr><td>1</td><td>a</td><td>2</td><td>b</td></tr>';
		$r .= '<tr><td>1</td><td>a</td><td>2</td><td>b</td></tr>';
		$r .= '<tr><td>1</td><td>a</td><td>2</td><td>b</td></tr>';		
	}
	if(strpos($qs,'updateinfo')){
		$r = file_get_contents("http://www.baidu.com/robots.txt");
		$r = '<tr><td>' . $r . '</td></tr>';
	}
	echo $r;

}

?>