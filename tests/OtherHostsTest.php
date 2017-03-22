<?php
namespace Xymon\Test;

use Xymon\Config\Hosts\Entry;
use Xymon\Config\Hosts;

class OtherHostsTest extends \PHPUnit_Framework_TestCase
{
    public $comment_chr = '#';

    public function testCommentEntry() {
        $comment1 = 'this is a comment';
        $comment2 = '# this is a comment';
        $obj1 = new Entry\CommentEntry($comment1);

        $this->assertEquals($obj1->getEntry(), $this->comment_chr . ' ' . $comment1);
        $this->assertEquals($obj1->getRaw(), $comment1);
        $this->assertEquals($obj1->getComment(), $comment1);

        $obj2 = new Entry\CommentEntry($comment2);
        $this->assertEquals($obj2->getEntry(), $comment2);
        $this->assertEquals($obj2->getRaw(), $comment2);
        $this->assertEquals($obj2->getComment(), $comment2);
    }

    public function testPageEntry() {
        $option = 'page';
        $name = 'example';
        $title = 'Example page';
        $comment = 'about this page';
        $page_string = "$option $name $title # $comment";
        $page_array = array($option, $name, $title);

        $obj = new Entry\PageEntry($option, $name, $title);
        $obj->setComment($comment);
        $this->assertEquals($obj->getOption(), $option);
        $this->assertEquals($obj->getName(), $name);
        $this->assertEquals($obj->getTitle(), $title);
        $this->assertEquals($obj->getComment(), $comment);
        $this->assertEquals($obj->getEntry(), $page_string);
        $this->assertEquals($obj->getArguments(), $page_array);
        $this->assertEquals($obj->getArgument(0), array_shift($page_array));
    }

    public function testSubparentEntry() {
        $option = 'page';
        $parent = 'parentpage';
        $name = 'example';
        $title = 'Example page';
        $comment = 'about this page';
        $page_string = "$option $parent $name $title # $comment";
        $page_array = array($option, $parent, $name, $title);

        $obj = new Entry\SubparentEntry($option, $parent, $name, $title);
        $obj->setComment($comment);
        $this->assertEquals($obj->getOption(), $option);
        $this->assertEquals($obj->getParent(), $parent);
        $this->assertEquals($obj->getName(), $name);
        $this->assertEquals($obj->getTitle(), $title);
        $this->assertEquals($obj->getComment(), $comment);
        $this->assertEquals($obj->getEntry(), $page_string);
        $this->assertEquals($obj->getArguments(), $page_array);
        $this->assertEquals($obj->getArgument(0), array_shift($page_array));
    }

    public function testGroupEntry() {
        $option = 'group';
        $title = 'Example group';
        $comment = 'about this group';
        $group_string = "$option $title # $comment";
        $group_array = array($option, $title);

        $obj1 = new Entry\GroupEntry($option, $title);
        $obj1->setComment($comment);
        $this->assertEquals($obj1->getOption(), $option);
        $this->assertEquals($obj1->getTitle(), $title);
        $this->assertEquals($obj1->getComment(), $comment);
        $this->assertEquals($obj1->getEntry(), $group_string);
        $this->assertEquals($obj1->getArguments(), $group_array);
        $this->assertEquals($obj1->getArgument(0), array_shift($group_array));
    }

    public function testIncludeEntry() {
        $path = '/etc/xymon/hosts.cfg';
        $obj = new Entry\IncludeEntry(Hosts::XMH_OPT_INCLUDE, $path);

        $this->assertEquals($obj->getOption(), Hosts::XMH_OPT_INCLUDE);
        $this->assertEquals($obj->getFilename(), $path);
        $this->assertEquals($obj->getEntry(), Hosts::XMH_OPT_INCLUDE . ' ' . $path);

        $obj->setOptional(true);
        $this->assertEquals($obj->getEntry(), Hosts::XMH_OPT_OPTIONAL . ' ' .  Hosts::XMH_OPT_INCLUDE . ' ' . $path);

        $str = "optional include $path # comment on this";
        $obj2 = new Entry\IncludeEntry($str);

        $this->assertEquals($obj2->isOptional(), true);
        $this->assertEquals($obj2->getOption(), Hosts::XMH_OPT_INCLUDE);
        $this->assertEquals($obj2->getFilename(), $path);
        $this->assertEquals($obj2->getEntry(), $str);
    }

    public function testDirectoryEntry() {
        $path = '/etc/xymon/hosts.d';
        $obj = new Entry\DirectoryEntry(Hosts::XMH_OPT_DIRECTORY, $path);

        $this->assertEquals($obj->getOption(), Hosts::XMH_OPT_DIRECTORY);
        $this->assertEquals($obj->getDirectory(), $path);
        $this->assertEquals($obj->getEntry(), Hosts::XMH_OPT_DIRECTORY . ' ' . $path);

        $obj->setOptional(true);
        $this->assertEquals($obj->getEntry(), Hosts::XMH_OPT_OPTIONAL . ' ' .  Hosts::XMH_OPT_DIRECTORY . ' ' . $path);

        $str = "optional\tdirectory\t$path\t# comment on this";
        $obj2 = new Entry\DirectoryEntry($str);

        $this->assertEquals($obj2->isOptional(), true);
        $this->assertEquals($obj2->getOption(), Hosts::XMH_OPT_DIRECTORY);
        $this->assertEquals($obj2->getDirectory(), $path);
        $this->assertEquals($obj2->getRaw(), $str);
        $this->assertEquals($obj2->getEntry(), str_replace("\t", ' ', $str));
        $this->assertEquals($obj2->getComment(), 'comment on this');
    }
}
