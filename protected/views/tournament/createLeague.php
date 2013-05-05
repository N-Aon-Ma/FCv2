<?php
/* @var $this TournamentController */
/* @var $model Tournament */
?>

<h1>Создать Турнир - Лигу</h1>

<?php
/* @var $this TournamentController */
/* @var $model Tournament */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'tournament-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <p class="note">Поля, отмеченные <span class="required">*</span> являются обязательными.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->dropDownList($model,'name', $data['league'], array('empty'=>'Выберите название:')); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'otherName'); ?>
        <?php echo $form->textField($model,'otherName'); ?>
        <?php echo $form->error($model,'otherName'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'prephase'); ?>
        <?php echo $form->checkBox($model, 'prephase'); ?>
        <?php echo $form->error($model,'prephase'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'number_of_groups'); ?>
        <?php echo $form->dropDownList($model,'number_of_groups',$data['groups'], array('onchange'=>'addGroup(); return false;', 'empty'=>'Выберите значение:')); ?>
        <?php echo $form->error($model,'number_of_groups'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'level'); ?>
        <?php echo $form->textField($model,'level'); ?>
        <?php echo $form->error($model,'level'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'number_of_rounds'); ?>
        <?php echo $form->dropDownList($model,'number_of_rounds', $data['rounds'], array('empty'=>'Выберите значение:')); ?>
        <?php echo $form->error($model,'number_of_rounds'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'rounds_of_semiseason'); ?>
        <?php echo $form->dropDownList($model,'rounds_of_semiseason', $data['semi'], array('empty'=>'Выберите значение:')); ?>
    </div>

    <div id="group1" class="del"> Группа 1
        <?php echo $form->labelEx($model,'team'); ?>
        <?php echo $form->dropDownList($model,'team',$teams, array(
            'onchange'=>'addField(1); return false;',
            'name'=>'Tournament[1][0]',
            'id'=>'Tournament_1_0'
        )); ?>
        <?php echo $form->error($model,'team'); ?>
    </div>

    <?php for($i=2; $i<33; $i++){
            echo '<div id="group',$i,'" class="del"></div>';
        }
    ?>

    <script>
        var count = new Array(32);
        for (var i=1; i<33; i++){
            count[i]=0;
        }
        var teams = <?php echo json_encode($teams); ?>;
        tempTeams = $.extend(true, [], teams);

        function add(gr){
            var newSelect = document.createElement('select');
            newSelect.setAttribute('onchange', 'addField('+gr+'); return false;');
            newSelect.setAttribute('name', 'Tournament['+gr+']['+count[gr]+']');
            var id = 'Tournament_'+gr+'_'+count[gr];
            newSelect.setAttribute('id', id);
            $('#group'+gr).append(newSelect);
            var sel = document.getElementById(id);
            for (var i in tempTeams){
                var option = document.createElement("option");
                option.text = tempTeams[i];
                option.value = i;
                sel.add(option);
            }
        }

        function addField(gr, del)
        {
            del = del || false;
            if (del==false){
                var tm = document.getElementById('Tournament_'+gr+'_'+count[gr]);
                if (tm.value!=0){
                    delete tempTeams[tm.value];
                    count[gr]++;
                    add(gr);
                }
            } else {
                add(gr);
            }
        }

        function addGroup(){
            var groups = document.getElementById('Tournament_number_of_groups').value;
            groups = parseInt(groups);
            tempTeams = $.extend(true, [], teams);
            $("div.del").empty();
            for (var i=1; i<=groups; i++){
                count[i] = 0;
                addField(i,true);
                count[i] = 0;
            }
       }
    </script>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->