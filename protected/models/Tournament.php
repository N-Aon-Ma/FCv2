<?php

/**
 * This is the model class for table "tournament".
 *
 * The followings are the available columns in table 'tournament':
 * @property integer $id
 * @property string $name
 * @property integer $season
 * @property string $create_date
 * @property integer $active
 * @property integer $type
 * @property integer $number_of_groups
 * @property integer $level
 * @property integer $number_of_rounds
 * @property string $label
 * @property integer $rounds_of_semiseason
 * @property integer $end
 */
class Tournament extends CActiveRecord
{
    const LEAGUE_TYPE = 0;
    const PLAY_OFF_TYPE = 1;
    public $otherName;
    public $team;
    public $stage;
    public $final = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tournament the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tournament';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, number_of_groups, number_of_rounds, level, rounds_of_semiseason', 'required', 'on'=>'createLeague'),
            array('name, stage, number_of_rounds, level, final', 'required', 'on'=>'createPlayOff'),
			array('season, active, type, number_of_groups, level, number_of_rounds, rounds_of_semiseason, end', 'numerical', 'integerOnly'=>true),
            array('type, prephase', 'in', 'range'=>array(0,1)),
			array('name', 'length', 'max'=>256),
            array('rounds_of_semiseason', 'compare','compareAttribute'=>'number_of_rounds', 'operator'=>'<='),
            array('otherName', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, season, create_date, active, type, number_of_groups, level, number_of_rounds, label, rounds_of_semiseason, end', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'season' => 'Сезон',
			'active' => 'Активный',
			'type' => 'Тип',
			'number_of_groups' => 'Количество групп',
			'level' => 'Уровень',
			'number_of_rounds' => 'Количество кругов',
			'label' => 'Метка',
			'rounds_of_semiseason' => 'Количество кругов, которые разрешено сыграть за полсезона',
            'otherName' => 'или другое (не карьера)',
            'prephase' => 'Предварительный раунд',
            'create_date' => 'Дата создания',
            'stage' => 'Стадия',
            'final' => 'Количество матчей в финале',
        );
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('season',$this->season);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('type',$this->type);
		$criteria->compare('number_of_groups',$this->number_of_groups);
		$criteria->compare('level',$this->level);
		$criteria->compare('number_of_rounds',$this->number_of_rounds);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('rounds_of_semiseason',$this->rounds_of_semiseason);
		$criteria->compare('end',$this->end);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function saveLeague($data){
        $transaction = $this->dbConnection->beginTransaction();
        $this->attributes = $data;
        $this->create_date = date('Y-m-d');
        $this->season = Yii::app()->config->get('season');
        $this->type = self::LEAGUE_TYPE;
        switch ($this->name){
            case 0 :
                $this->label = 'la';
                $this->name = 'Лига A';
                $this->prephase = 0;
                break;
            case 1 :
                $this->label = 'lb';
                $this->name = 'Лига B';
                $this->prephase = 0;
                break;
            case 2 :
                $this->label = 'lc';
                $this->name = 'Лига Чемпионов. Групповой этап';
                $this->prephase = 1;
                break;
            case 3 :
                $this->label = 'le';
                $this->name = 'Лига Европы. Групповой этап';
                $this->prephase = 1;
                break;
            case 4 :
                $this->label = 'nc';
                $this->name = $this->otherName;
                break;
        }
        if (!$this->save()){
            $transaction->rollback();
            return false;
        }

        //Проверка, что все команды разные
        for ($i=1; $i<=$this->number_of_groups; $i++){
            for ($j=0; $j<count($data[$i])-1; $j++){
                for ($k=$i; $k<=$this->number_of_groups; $k++){
                    for ($l=$j; $l<count($data[$i])-1; $l++){
                        if ($i!=$k || $j!=$l){
                            if ($data[$i][$j]==$data[$k][$l] || $data[$i][$j]==0 || $data[$k][$l]==0){
                                $transaction->rollback();
                                $this->addError('team', 'Команды должны быть разными.');
                                return false;
                            }
                        }
                    }
                }
            }
        }

        //Создание матчей
        for ($i=1; $i<=$this->number_of_groups; $i++){
            for ($j=0; $j<count($data[$i])-1; $j++){
                for ($k=1; $k<=$this->number_of_rounds; $k++){
                    for ($l = $j+1; $l<count($data[$i])-1; $l++){
                        $match = new Match('create');
                        $match->tournament_id = $this->id;
                        $this->label=='nc' ? $match->career = false : $match->career = true;
                        $k<=$this->rounds_of_semiseason ? $match->enable = true : $match->enable = false;
                        $match->group = $i;
                        $match->home_team_id = $data[$i][$j];
                        $match->away_team_id = $data[$i][$l];

                        if (!$match->save()){
                            $transaction->rollback();
                            $this->addError('team', 'Ошибка при сохранении матча.');
                            return false;
                        }
                    }
                }
            }
        }

        $transaction->commit();
        return true;

    }

    public function savePlayOff($data){
        $transaction = $this->dbConnection->beginTransaction();
        $this->attributes = $data;
        $this->create_date = date('Y-m-d');
        $this->season = Yii::app()->config->get('season');
        $this->type = self::PLAY_OFF_TYPE;
        switch ($this->name){
            case 0 :
                $this->label = 'lc';
                $this->name = 'Лига Чемпионов. Плей-офф';
                break;
            case 1 :
                $this->label = 'le';
                $this->name = 'Лига Европы. Плей-офф';
                $this->type = 1;
                break;
            case 2 :
                $this->label = 'c';
                $this->name = 'Кубок';
                break;
            case 3 :
                $this->label = 'sl';
                $this->name = 'Суперкубок Лиги';
                break;
            case 4 :
                $this->label = 'se';
                $this->name = 'Суперкубок Европы';
                break;
            case 5 :
                $this->label = 'gc';
                $this->name = 'Golden Cup';
                break;
            case 6 :
                $this->label = 'nc';
                $this->name = $this->otherName;
                break;
        }
        if (!$this->save()){
            $transaction->rollback();
            return false;
        }

        if ($this->prephase){
            $groups = $data['choiceList'];

            //Проверка, что все команды разные
            for ($i=1; $i<=$groups; $i++){
                for ($j=0; $j<=1; $j++){
                    for ($k=$i; $k<=$groups; $k++){
                        for ($l=$j; $l<=1; $l++){
                            if ($i!=$k || $j!=$l){
                                if ($data[$i][$j]==$data[$k][$l] || $data[$i][$j]==0 || $data[$k][$l]==0){
                                    $transaction->rollback();
                                    $this->addError('team', 'Команды должны быть разными.');
                                    return false;
                                }
                            }
                        }
                    }
                }
            }

            for ($j = 1; $j<=$this->number_of_rounds; $j++){
                for ($i=1; $i<=$groups; $i++){
                    $match = new Match('create');
                    $match->tournament_id = $this->id;
                    $this->label=='nc' ? $match->career = false : $match->career = true;
                    $match->enable = true;
                    if ($j%2==0){//делаем матчи дома-в гостях
                        $num1 = 0;
                        $num2 = 1;
                    } else {
                        $num1 = 1;
                        $num2 = 0;
                    }
                    $match->home_team_id = $data[$i][$num1];
                    $match->away_team_id = $data[$i][$num2];
                    if (!$match->save()){
                        $transaction->rollback();
                        $this->addError('team', 'Ошибка при сохранении матча.');
                        return false;
                    }
                }
            }
        } else{
            $groups = $this->stage;

            //Проверка, что все команды разные
            for ($i=1; $i<=$groups; $i++){
                for ($j=0; $j<=1; $j++){
                    for ($k=$i; $k<=$groups; $k++){
                        for ($l=$j; $l<=1; $l++){
                            if ($i!=$k || $j!=$l){
                                if ($data[$i][$j]==$data[$k][$l] || $data[$i][$j]==0 || $data[$k][$l]==0){
                                    $transaction->rollback();
                                    $this->addError('team', 'Команды должны быть разными.');
                                    return false;
                                }
                            }
                        }
                    }
                }
            }

            for ($k=$this->stage; $k>1; $k/=2){
                for ($j = 1; $j<=$this->number_of_rounds; $j++){
                    for ($i=1; $i<=$groups; $i++){
                        $match = new Match('create');
                        $match->tournament_id = $this->id;
                        $this->label=='nc' ? $match->career = false : $match->career = true;
                        $match->enable = true;
                        $match->stage = $k;
                        if ($k==$this->stage){
                            if ($j%2==0){//делаем матчи дома-в гостях
                                $num1 = 0;
                                $num2 = 1;
                            } else {
                                $num1 = 1;
                                $num2 = 0;
                            }
                            $match->home_team_id = $data[$i][$num1];
                            $match->away_team_id = $data[$i][$num2];
                        }
                        if (!$match->save()){
                            $transaction->rollback();
                            $this->addError('team', 'Ошибка при сохранении матча.');
                            return false;
                        }
                    }
                }
            }

            //а теперь - финал!!
            for ($j = 1; $j<=$this->final; $j++){
                $match = new Match('create');
                $match->tournament_id = $this->id;
                $this->label=='nc' ? $match->career = false : $match->career = true;
                $match->enable = true;
                $match->stage = 1;
                if ($this->stage==1){
                    if ($j%2==0){//делаем матчи дома-в гостях
                        $num1 = 0;
                        $num2 = 1;
                    } else {
                        $num1 = 1;
                        $num2 = 0;
                    }
                    $match->home_team_id = $data[1][$num1];
                    $match->away_team_id = $data[1][$num2];
                }
                if (!$match->save()){
                    $transaction->rollback();
                    $this->addError('team', 'Ошибка при сохранении матча.');
                    return false;
                }
            }

        }

        $transaction->commit();
        return true;

    }

    public function getDataForCreateLeague(){
        $data['league'][0] = 'Лига A';
        $data['league'][1] = 'Лига B';
        $data['league'][2] = 'Лига Чемпионов. Групповой этап';
        $data['league'][3] = 'Лига Европы. Групповой этап';
        $data['league'][4] = 'Другое';

        $data['groups'][1] = 1;
        $data['groups'][2] = 2;
        $data['groups'][4] = 4;
        $data['groups'][8] = 8;
        $data['groups'][16] = 16;
        $data['groups'][32] = 32;

        $data['rounds'][1] = 1;
        $data['rounds'][2] = 2;
        $data['rounds'][3] = 3;
        $data['rounds'][4] = 4;

        $data['semi'][1] = 1;
        $data['semi'][2] = 2;
        $data['semi'][3] = 3;
        $data['semi'][4] = 4;

        return $data;
    }

    public function getDataForCreatePlayOff(){
        $data['league'][0] = 'Лига Чемпионов. Плей-офф';
        $data['league'][1] = 'Лига Европы. Плей-офф';
        $data['league'][2] = 'Кубок';
        $data['league'][3] = 'Суперкубок Лиги';
        $data['league'][4] = 'Суперкубок Европы';
        $data['league'][5] = 'Golden Cup';
        $data['league'][6] = 'Другое';

        $data['stage'][32] = '1/32';
        $data['stage'][16] = '1/16';
        $data['stage'][8] = '1/8';
        $data['stage'][4] = '1/4';
        $data['stage'][2] = '1/2';
        $data['stage'][1] = 'Финал';

        $data['rounds'][1] = 1;
        $data['rounds'][2] = 2;

        $data['final'][1] = 1;
        $data['final'][2] = 2;

        return $data;
    }
}