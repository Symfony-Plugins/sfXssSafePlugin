<?php
 
// initializes testing framework
$app = 'frontend';
$sf_root = dirname(__FILE__).'/../../../..';
require_once($sf_root.'/lib/symfony/vendor/lime/lime.php');
include($sf_root.'/test/bootstrap/functional.php');

// add filters to the default configuration
$definitions = array(
  'Attr' =>
    array(
      'AllowedFrameTargets' => array('_blank'),
      'EnableID' => true
    ),
  'Filter' => 
    array(
      'YouTube' => true,
    ),
  'URI' =>
    array(
      'HostBlacklist' => array ('www.symfony-project.org')
    )
);

// force configuration
sfConfig::set('app_sfXssSafePlugin_definition', $definitions);

$xsssafe_tests = array(
  'XSS Quick Test' => array(
    'input'   => '\'\';!--"<XSS>=&{()}',
    'output'  => '\'\';!--&quot;=&amp;{()}'
  ),
  'SCRIPT w/Alert()' => array(
    'input'   => '<SCRIPT>alert(\'XSS\')</SCRIPT>',
    'output'  => ''
  ),
  'SCRIPT w/Source File' => array(
    'input'   => '<SCRIPT SRC=http://ha.ckers.org/xss.js></SCRIPT>',
    'output'  => ''
  ),
  'SCRIPT w/Char Code' => array(
    'input'   => '<SCRIPT>alert(String.fromCharCode(88,83,83))</SCRIPT>',
    'output'  => ''
  ),
  'BASE' => array(
    'input'   => '<BASE HREF="javascript:alert(\'XSS\');//">',
    'output'  => ''
  ),
  'BGSOUND' => array(
    'input'   => '<SCRIPT>alert(String.fromCharCode(88,83,83))</SCRIPT>',
    'output'  => ''
  ),
  'BODY background-image' => array(
    'input'   => '<BODY BACKGROUND="javascript:alert(\'XSS\');">',
    'output'  => ''
  ),
  'BODY ONLOAD' => array(
    'input'   => '<BODY ONLOAD=alert(\'XSS\')>',
    'output'  => ''
  ),
  'DIV background-image' => array(
    'input'   => '<DIV STYLE="background-image: url(javascript:alert(\'XSS\'))">',
    'output'  => '<div></div>'
  ),
  'DIV expression' => array(
    'input'   => '<DIV STYLE="width: expression(alert(\'XSS\'));">',
    'output'  => '<div></div>'
  ),
  'FRAME' => array(
    'input'   => '<FRAMESET><FRAME SRC="javascript:alert(\'XSS\');"></FRAMESET>',
    'output'  => ''
  ),
  'IFRAME' => array(
    'input'   => '<IFRAME SRC="javascript:alert(\'XSS\');"></IFRAME>',
    'output'  => ''
  ),
  'IMG w/JavaScript Directive' => array(
    'input'   => '<IMG SRC="javascript:alert(\'XSS\');">',
    'output'  => ''
  ),
  'IMG No Quotes/Semicolon' => array(
    'input'   => '<IMG SRC=javascript:alert(\'XSS\')>',
    'output'  => ''
  ),
  'IMG Dynsrc' => array(
    'input'   => '<IMG DYNSRC="javascript:alert(\'XSS\');">',
    'output'  => ''
  ),
  'IMG Lowsrc' => array(
    'input'   => '<IMG LOWSRC="javascript:alert(\'XSS\');">',
    'output'  => ''
  ),
  'IMG Embedded commands 1' => array(
    'input'   => '<IMG SRC="http://www.thesiteyouareon.com/somecommand.php?somevariables=maliciouscode">',
    'output'  => '<img src="http://www.thesiteyouareon.com/somecommand.php?somevariables=maliciouscode" alt="somecommand.php?somevariables=maliciouscode" />'
  ),
  'IMG STYLE w/expression' => array(
    'input'   => 'exp/*<XSS STYLE=\'no\xss:noxss("*//*"); xss:&#101;x&#x2F;*XSS*//*/* /pression(alert("XSS"))\'>',
    'output'  => 'exp/*'
  ),
  'List-style-image' => array(
    'input'   => '<STYLE>li {list-style-image: url("javascript:alert(\'XSS\')");}</STYLE><UL><LI>XSS',
    'output'  => '<ul><li>XSS</li></ul>'
  ),
  'IMG w/VBscript' => array(
    'input'   => '<IMG SRC=\'vbscript:msgbox("XSS")\'>',
    'output'  => ''
  ),
  'LAYER' => array(
    'input'   => '<LAYER SRC="http://ha.ckers.org/scriptlet.html"></LAYER>',
    'output'  => ''
  ),
  'Livescript' => array(
    'input'   => ' <IMG SRC="livescript:[code]">',
    'output'  => ''
  ),
  'META' => array(
    'input'   => '<META HTTP-EQUIV="refresh" CONTENT="0;url=javascript:alert(\'XSS\');">',
    'output'  => ''
  ),
  'META w/data:URL' => array(
    'input'   => '<META HTTP-EQUIV="refresh" CONTENT="0;url=data:text/html;base64,PHNjcmlwdD5hbGVydCgnWFNTJyk8L3NjcmlwdD4K">',
    'output'  => ''
  ),
  'META w/additional URL parameter' => array(
    'input'   => '<META HTTP-EQUIV="refresh" CONTENT="0; URL=http://;URL=javascript:alert(\'XSS\');">',
    'output'  => ''
  ),
  'OBJECT' => array(
    'input'   => '<OBJECT TYPE="text/x-scriptlet" DATA="http://ha.ckers.org/scriptlet.html"></OBJECT>',
    'output'  => ''
  ),
  'OBJECT w/Embedded XSS' => array(
    'input'   => '<OBJECT classid=clsid:ae24fdae-03c6-11d1-8b76-0080c744f389><param name=url value=javascript:alert(\'XSS\')></OBJECT>',
    'output'  => ''
  ),
  'Embed Flash' => array(
    'input'   => '<EMBED SRC="http://ha.ckers.org/xss.swf" AllowScriptAccess="always"></EMBED>',
    'output'  => ''
  ),
  'STYLE' => array(
    'input'   => '<STYLE TYPE="text/javascript">alert(\'XSS\');</STYLE>',
    'output'  => ''
  ),
  'STYLE w/Comment' => array(
    'input'   => '<IMG STYLE="xss:expr/*XSS*/ession(alert(\'XSS\'))">',
    'output'  => ''
  ),
  'STYLE w/Anonymous HTML' => array(
    'input'   => '<XSS STYLE="xss:expression(alert(\'XSS\'))">',
    'output'  => ''
  ),
  'STYLE w/background-image' => array(
    'input'   => '<STYLE>.XSS{background-image:url("javascript:alert(\'XSS\')");}</STYLE><A CLASS=XSS></A>',
    'output'  => '<a class="XSS"></a>'
  ),
  'STYLE w/background' => array(
    'input'   => '<STYLE type="text/css">BODY{background:url("javascript:alert(\'XSS\')")}</STYLE>',
    'output'  => ''
  ),
  'Stylesheet' => array(
    'input'   => '<LINK REL="stylesheet" HREF="javascript:alert(\'XSS\');">',
    'output'  => ''
  ),
  'Remote Stylesheet' => array(
    'input'   => '<LINK REL="stylesheet" HREF="http://ha.ckers.org/xss.css">',
    'output'  => ''
  ),
  'TABLE' => array(
    'input'   => '<TABLE><TD BACKGROUND="javascript:alert(\'XSS\')"></TD></TABLE>',
    'output'  => ''
  ),
  'PHP' => array(
    'input'   => '<? echo(\'<SCR)\'; echo(\'IPT>alert("XSS")</SCRIPT>\'); ?>',
    'output'  => ' echo(\'alert(&quot;XSS&quot;)\'); ?&gt;'
  ),
  'Character Encoding Example' => array(
    'input'   => '<
%3C
&lt
&lt;
&LT
&LT;
&#60

&#060
&#0060

&#00060
&#000
060
&#0000060
&#60;
&#060;
&
#0060;
&#00060;
&#000060;
&#
0000060;
&#x3c
&#x03c
&#x003
c
&#x0003c
&#x00003c
&#x0000
03c
&#x3c;
&#x03c;

&#x003c;

&#x0003c;
&#x00003c;
&#x000
003c;
&#X3c
&#X03c
&#X003c
&
#X0003c
&#X00003c
&#X000003c

&#X3c;
&#X03c;
&#X003c;
&#X
0003c;
&#X00003c;
&#X000003c
;
&#x3C

&#x03C
&#x003C
&#x0
003C
&#x00003C
&#x000003C
&#
x3C;
&#x03C;
&#x003C;
&#x000
3C;
&#x00003C;
&#x000003C;
&
#X3C
&#X03C
&#X003C
&#X0003C

&#X00003C
&#X000003C

&#X3C
;
&#X03C;
&#X003C;
&#X0003C;

&#X00003C;
&#X000003C;
\x3c

\x3C
\u003c
\u003C',
    'output'  => ''
  ),
  'JavaScript Link Location' => array(
    'input'   => '<A HREF="javascript:document.location=\'http://www.google.com/\'">XSS</A>',
    'output'  => '<a>XSS</a>'
  ),
  'Case Insensitive' => array(
    'input'   => '<IMG
SRC=JaVaScRiPt:alert(\'XSS\')>',
    'output'  => ''
  ),
  'HTML Entities' => array(
    'input'   => '<IMG
SRC=javascript:alert(&quot;X
SS&quot;)>',
    'output'  => ''
  ),
  'Grave Accents' => array(
    'input'   => '<IMG
SRC=`javascript:alert("RSnak
e says, \'XSS\'")`>',
    'output'  => ''
  ),
  'Image w/CharCode' => array(
    'input'   => '<IMG
SRC=javascript:alert(String.
fromCharCode(88,83,83))>',
    'output'  => ''
  ),
  'Escaping JavaScript escapes' => array(
    'input'   => <<<END
\";alert('XSS');//
END
,
    'output'  => <<<END
\&quot;;alert('XSS');//
END
  ),
  'End title tag' => array(
    'input'   => '</TITLE><SCRIPT>alert("XSS")
;</SCRIPT>',
    'output'  => ''
  ),
  'STYLE w/broken up JavaScript' => array(
    'input'   => <<<END
    <STYLE>@im\port'\ja\vasc\rip
t:alert("XSS")';</STYLE>
END
,
    'output'  => ''
  ),
  'Embedded Tab' => array(
    'input'   => '<IMG
SRC="jav\tascript:alert(\'XSS\'
);">',
    'output'  => ''
  ),
  'Embedded Encoded Tab' => array(
    'input'   => '<IMG
SRC="jav&#x09;ascript:alert(
\'XSS\');">',
    'output'  => ''
  ),
  'Embedded Newline' => array(
    'input'   => '<IMG
SRC="jav&#x0A;ascript:alert(
\'XSS\');">',
    'output'  => ''
  ),
  'Embedded Carriage Return' => array(
    'input'   => '<IMG
SRC="jav&#x0D;ascript:alert(
\'XSS\');">',
    'output'  => ''
  ),
  'Multiline w/Carriage Returns' => array(
    'input'   => <<<END
<IMG
SRC
=
"
j
a
v
a
s
c
r
i
p
t
:
a
l
e
r
t
(
'
X
S
S
'
)
"
>
END
,
    'output'  => ''
  ),
  'Firefox Lookups' => array(
    'input'   => '<A HREF="http://google:ha.ckers.org">XSS</A>',
    'output'  => '<a href="http://google">XSS</a>'
  ),
  'Content Replace' => array(
    'input'   => '<A HREF="http://www.gohttp://www.google.com/ogle.com/">XSS</A>',
    'output'  => '<a href="http://www.gohttp//www.google.com/ogle.com/">XSS</a>'
  ),
  'Mixed Encoding' => array(
    'input'   => <<<END
<A HREF="htt\tp://6&#09;6.000146.0x7.147/">XSS</A>
END
,
    'output'  => '<a>XSS</a>'
  )
);

$miscellaneous_tests = array(
  'YouTube Filter' => array(
    'input'   => '<object width="425" height="355"><param name="movie" value="http://www.youtube.com/v/HLHKgepRZ8M&hl=fr"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/HLHKgepRZ8M&hl=fr" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object>',
    'output'  => '<object width="425" height="350" data="http://www.youtube.com/v/HLHKgepRZ8M"><param name="movie" value="http://www.youtube.com/v/HLHKgepRZ8M"></param><param name="wmode" value="transparent"></param><!--[if IE]><embed src="http://www.youtube.com/v/HLHKgepRZ8M"type="application/x-shockwave-flash"wmode="transparent" width="425" height="350" /><![endif]--></object>',
    'filter'   => true
  ),
  'Allowed Frame Targets Filter' => array(
    'input'   => '<a href="" target="_blank"></a>',
    'output'  => '<a href="" target="_blank"></a>',
    'filter'   => true
  ),
  'Enable ID' => array(
    'input'   => '<div id="test"></div>',
    'output'  => '<div id="test"></div>',
    'filter'   => true
  ),
  'Host Blacklist' => array(
    'input'   => '<a href="http://www.symfony-project.org/">Symfony Project</a>',
    'output'  => '<a>Symfony Project</a>',
    'filter'   => true
  )
);
 
$t = new lime_test(count($xsssafe_tests)+count($miscellaneous_tests)+2, new lime_output_color());
 
// XssSafe Helper
$t->diag('XssSafe Helper');
$t->include_ok(sfConfig::get('sf_plugins_dir').'/sfXssSafePlugin/lib/helper/XssSafeHelper.php', 'XssSafe Helper include');
$t->include_ok(sfConfig::get('sf_plugins_dir').'/sfXssSafePlugin/lib/vendor/htmlpurifier/HTMLPurifier.auto.php', 'HTML Purifier include');

// XSS Attacks Smoketest
$t->diag('XSS Attacks Smoketest');
foreach ($xsssafe_tests as $name => $test)
{
  $t->is(esc_xsssafe($test['input']), $test['output'], $name . sprintf('%s', isset($test['filter']) ? ' is properly filtered' : ' is properly escaped'));
}

// HTML Purifier Config
$t->diag('HTML Purifier Config');
foreach ($miscellaneous_tests as $name => $test)
{
  $t->is(esc_xsssafe($test['input']), $test['output'], $name . sprintf('%s', isset($test['filter']) ? ' is properly filtered' : ' is properly escaped'));
}

?>