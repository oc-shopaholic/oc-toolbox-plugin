<?php namespace Lovata\Toolbox\Classes\Event;

use Lovata\Toolbox\Models\Settings;
use Lovata\Toolbox\Models\ImageProfile;

/**
 * Class AbstractImageSetsFieldHandler
 * Adds "image_sets" repeater field to model form.
 * Model must declare "image_sets" relation: ['image_sets' => [ImageSet::class, 'name' => 'imageable', 'delete' => true]]
 * @package Lovata\Toolbox\Classes\Event
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 */
abstract class AbstractImageSetsFieldHandler extends AbstractBackendFieldHandler
{
    /**
     * Extend backend fields
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        if (!$this->isAvailable()) {
            return;
        }

        $sTabName = $this->getTabName();

        $obWidget->addTabFields([
            'image_sets_section' => [
                'label' => 'lovata.toolbox::lang.field.image_sets',
                'tab'   => $sTabName,
                'span'  => 'full',
                'type'  => 'section',
            ],
            'image_sets'         => [
                'tab'         => $sTabName,
                'span'        => 'full',
                'type'        => 'repeater',
                'useRelation' => true,
                'form'        => '$/lovata/toolbox/models/imageset/fields.yaml',
            ],
        ]);
    }

    /**
     * Image sets are enabled in settings and at least one active profile exists
     * @return bool
     */
    protected function isAvailable() : bool
    {
        if (!Settings::getValue('image_sets_enabled')) {
            return false;
        }

        return ImageProfile::active()->exists();
    }

    /**
     * Get tab name for image sets field
     * @return string
     */
    abstract protected function getTabName() : string;
}
