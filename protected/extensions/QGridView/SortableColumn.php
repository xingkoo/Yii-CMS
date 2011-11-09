<?php
class SortableColumn extends CDataColumn
{
    public $cssClass = null;
    public $headerText = null;
    public $value = 3;
    public $assets;

    public function init()
    {
        parent::init();

        $this->assets = Yii::app()
            ->getAssetManager()
            ->publish(Yii::getPathOfAlias('ext.QGridView.assets'));
        $this->registerClientScript();
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript()
    {
        $id    = $this->grid->getId();
        $url   = Yii::app()->createUrl("/content/helpAdmin/sortable");
        $model = $this->grid->dataProvider->modelClass;

        $options = CJavaScript::encode(array(
            'id'    => $id,
            'url'   => $url,
            'model' => $model
        ));

        Yii::app()
            ->getClientScript()
            ->registerCoreScript('jquery.ui')
            ->registerScriptFile($this->assets.'/sortableCGrid.js')
            ->registerScript('sort_grid_'.$id, "$('#{$id} > table > tbody').sortableCGrid({$options});");
    }

    protected function renderHeaderCellContent()
    {
        if ($this->headerText != null)
        {
            echo $this->headerText;
        }
        else
        {
            parent::renderHeaderCellContent();
        }
    }

    public function renderDataCellContent()
    {
        echo "<div class='positioner'><img src='".$this->assets."/img/hand.png' width='16'></div>";
    }

    public function renderDataCell($row)
    {
        $data    = $this->grid->dataProvider->data[$row];
        $options = $this->htmlOptions;
        $pk      = $this->grid->dataProvider->data[$row]->getPrimaryKey();
        if ($this->cssClassExpression !== null)
        {
            $class = $this->evaluateExpression($this->cssClassExpression, array(
                'row' => $row,
                'data'=> $data
            ));
            if (isset($options['class']))
            {
                $options['class'] .= 'pk '.$class;
            }
            else
            {
                $options['class'] = $class;
            }
        }
        else
        {
            $options['class'] = 'pk';
        }

        $options['id'] = 'pk_'.$pk;

        echo CHtml::openTag('td', $options);
        $this->renderDataCellContent($row, $data);
        echo '</td>';
    }

}
