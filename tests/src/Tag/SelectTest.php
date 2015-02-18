<?php
namespace Sirius\Html\Tag;

use Sirius\Html\TagSelect;

class SelectTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->input = new Select(array(
            'name' => 'answer',
            'class' => 'dropdown'
        ), null, array(
            'value' => 'maybe',
            'first_option' => '--select--',
            'options' => array(
                'yes' => 'Yes',
                'no' => 'No',
                'maybe' => 'Maybe'
            )
        ));
    }

    function testRender()
    {
        $result = (string) $this->input;
        $this->assertTrue(strpos($result, '<select class="dropdown"') !== false);
        $this->assertTrue(strpos($result, '<option value="maybe" selected="selected">Maybe</option>') !== false);
        $this->assertTrue(strpos($result, '<option value="yes" >Yes</option>') !== false);
    }
}
