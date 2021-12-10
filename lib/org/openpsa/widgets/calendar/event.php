<?php
/**
 * @package org.openpsa.widgets
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * Simple event class for feeding event information to the calendar widgets
 *
 * @package org.openpsa.widgets
 */
class org_openpsa_widgets_calendar_event
{
    /**
     * Event GUID
     *
     * @var string
     */
    public $guid = '';

    /**
     * Defines the start of an event - this property is required for calendar to work
     *
     * @var integer
     */
    public $start = 0;

    /**
     * Defines the end of an event
     *
     * @var integer
     */
    public $end = 0;

    /**
     * Link to the event
     *
     * @var string
     */
    var $link = '';

    /**
     * Attributes for the event
     *
     * @var string
     */
    var $attributes = '';

    /**
     * Event title
     *
     * @var string
     */
    var $title = '';

    /**
     * Event description
     *
     * @var string
     */
    public $description = '';

    /**
     * Event location
     *
     * @var string
     */
    public $location = '';

    /**
     * Event CSS class
     *
     * @var string
     */
    public $class = '';

    /**
     * Actual event object
     *
     * @var org_openpsa_calendar_event_dba
     */
    var $event;

    public function __construct(org_openpsa_calendar_event_dba $event = null)
    {
        if ($event) {
            $this->event = $event;

            // Read values from event object
            $this->start = $event->start;
            $this->end = $event->end;
            $this->title = $event->title;
            $this->location = $event->location;
        }
    }

    /**
     * Draws links to the right location
     */
    private function _render_link() : string
    {
        if ($this->attributes != '') {
            return "<a class=\"url\" {$this->attributes}>{$this->title}</a>";
        }
        if (!$this->link) {
            return $this->title;
        }

        return "<a class=\"url\" href=\"{$this->link}\" title=\"{$this->title}\">{$this->title}</a>";
    }

    /**
     * Renders hEvent compatible and nice time label
     */
    public function render_timelabel(bool $show_day_name = false) : string
    {
        $formatter = midcom::get()->i18n->get_l10n()->get_formatter();

        $dtstart = date('c', $this->start);
        $dtend = date('c', $this->end);
        $timelabel = "        <abbr class=\"dtstart\" title=\"{$dtstart}\">";
        $separator = "</abbr> &ndash; <abbr class=\"dtend\" title=\"{$dtend}\">";
        $timelabel .= $formatter->timeframe($this->start, $this->end, 'both', $separator, $show_day_name);
        $timelabel .= '</abbr>';
        return $timelabel;
    }

    /**
     * Draws one single event
     */
    public function render(string $element = 'div', int $h_level = 3) : string
    {
        $rendered_event = "<{$element} class=\"vevent\">\n";
        $rendered_event .= $this->render_timelabel();

        if ($this->title) {
            $rendered_event .= "    <h{$h_level} class=\"summary\">" . $this->_render_link() . "</h{$h_level}>\n";
        }

        if ($this->location != '') {
            $rendered_event .= "    <div class=\"location\">{$this->location}</div>\n";
        }

        if ($this->event !== null) {
            $rendered_event .= "    <span class=\"uid\" style=\"display: none;\">{$this->event->guid}</span>\n";
        }
        $rendered_event .= "</{$element}>\n";

        return $rendered_event;
    }
}
