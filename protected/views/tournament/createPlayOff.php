<?php
/* @var $this TournamentController */
/* @var $model Tournament */
?>

<h1>Создать Турнир - Плей-офф</h1>

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
        <?php echo $form->labelEx($model,'stage'); ?>
        <?php echo $form->dropDownList($model,'stage', $data['stage'], array('empty'=>'Выберите значение:', 'onchange'=>'addField(); return false;')); ?>
        <?php echo $form->error($model,'stage'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'prephase'); ?>
        <?php echo $form->checkBox($model, 'prephase', array('onclick'=>'addChoice(); return true;')); ?>
        <?php echo $form->error($model,'prephase'); ?>
    </div>

    <div id='choice'></div>

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
        <?php echo $form->labelEx($model,'final'); ?>
        <?php echo $form->dropDownList($model,'final', $data['final'], array('empty'=>'Выберите значение:')); ?>
        <?php echo $form->error($model,'final'); ?>
    </div>

    <?php for($i=1; $i<33; $i++){
        echo '<div id="group',$i,'" class="del"></div>';
    }
    ?>

    <script>
        var teams = <?php echo json_encode($teams); ?>;

        function addChoice(){
            $("div#choice").empty();
            $("div.del").empty();
            var chk = document.getElementById('Tournament_prephase');
            if (chk.checked){
                var newSelect = document.createElement('select');
                newSelect.setAttribute('onchange', 'addField(true); return false;');
                newSelect.setAttribute('name', 'Tournament[choiceList]');
                newSelect.setAttribute('id', 'Tournament_choiceList');
                $('#choice').append('<b>Количество матчей предварительного этапа</b><br />');
                $('#choice').append(newSelect);
                var sel = document.getElementById('Tournament_choiceList');
                for (var i=1; i<=32; i++){
                    var option = document.createElement("option");
                    option.text = i;
                    option.value = i;
                    sel.add(option);
                }
                sel.selectedIndex = 0;
            }
        }

        function add(gr){
            for (var j=0; j<=1; j++){
                var newSelect = document.createElement('select');
                newSelect.setAttribute('name', 'Tournament['+gr+']['+j+']');
                var id = 'Tournament_'+gr+'_'+j;
                newSelect.setAttribute('id', id);
                $('#group'+gr).append(newSelect);
                var sel = document.getElementById(id);
                for (var i in teams){
                    var option = document.createElement("option");
                    option.text = teams[i];
                    option.value = i;
                    sel.add(option);
                }
                if (j==0){
                    $('#group'+gr).append(' vs ');
                }
            }
        }

        function addField(choice)
        {
            choice = choice || false;
            $("div.del").empty();
            var en = true;
            if (choice){
                var sel = document.getElementById('Tournament_choiceList');
                var len = sel.value;
            } else{
                var prephase = document.getElementById('Tournament_prephase');
                if (prephase.checked){
                    en = false;
                }
                var sel = document.getElementById('Tournament_stage');
                var len = sel.value;
            }
            if (en){
                for (var k=1; k<=len; k++){
                    add(k);
                }
            }
        }

//        function addGroup(){
//            var groups = document.getElementById('Tournament_number_of_groups').value;
//            groups = parseInt(groups);
//            tempTeams = $.extend(true, [], teams);
//            $("div.del").empty();
//            for (var i=1; i<=groups; i++){
//                count[i] = 0;
//                addField(i,true);
//                count[i] = 0;
//            }
//        }
    </script>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->