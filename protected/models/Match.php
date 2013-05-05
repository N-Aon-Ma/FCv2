<?php

/**
 * This is the model class for table "match".
 *
 * The followings are the available columns in table 'match':
 * @property integer $id
 * @property integer $home_team_id
 * @property integer $away_team_id
 * @property integer $home_goals
 * @property integer $away_goals
 * @property string $date
 * @property integer $tournament_id
 * @property integer $home_coach_id
 * @property integer $away_coach_id
 * @property integer $confirm
 * @property integer $stage
 * @property integer $group
 * @property integer $career
 * @property integer $enable
 * @property integer $rating_change
 *
 * The followings are the available model relations:
 * @property Tournament $tournament
 * @property User $homeCoach
 * @property User $awayCoach
 */
class Match extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Match the static model class
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
		return 'match';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
//	public function rules()
//	{
//		// NOTE: you should only define rules for those attributes that
//		// will receive user inputs.
//		return array(
//			array('home_team_id, away_team_id, home_goals, away_goals, date, tournament_id, home_coach_id, away_coach_id, confirm, stage, group, enable, rating_change', 'required', 'except'=>'create'),
//			array('home_team_id, away_team_id, home_goals, away_goals, tournament_id, home_coach_id, away_coach_id, confirm, stage, group, career, enable, rating_change', 'numerical', 'integerOnly'=>true),
//			// The following rule is used by search().
//			// Please remove those attributes that should not be searched.
//			array('id, home_team_id, away_team_id, home_goals, away_goals, date, tournament_id, home_coach_id, away_coach_id, confirm, stage, group, career, enable, rating_change', 'safe', 'on'=>'search'),
//		);
//	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tournament' => array(self::BELONGS_TO, 'Tournament', 'tournament_id'),
			'homeCoach' => array(self::BELONGS_TO, 'User', 'home_coach_id'),
			'awayCoach' => array(self::BELONGS_TO, 'User', 'away_coach_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'home_team_id' => 'Home Team',
			'away_team_id' => 'Away Team',
			'home_goals' => 'Home Goals',
			'away_goals' => 'Away Goals',
			'date' => 'Date',
			'tournament_id' => 'Tournament',
			'home_coach_id' => 'Home Coach',
			'away_coach_id' => 'Away Coach',
			'confirm' => 'Confirm',
			'stage' => 'Stage',
			'group' => 'Group',
			'career' => 'Career',
			'enable' => 'Enable',
			'rating_change' => 'Rating Change',
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
		$criteria->compare('home_team_id',$this->home_team_id);
		$criteria->compare('away_team_id',$this->away_team_id);
		$criteria->compare('home_goals',$this->home_goals);
		$criteria->compare('away_goals',$this->away_goals);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('tournament_id',$this->tournament_id);
		$criteria->compare('home_coach_id',$this->home_coach_id);
		$criteria->compare('away_coach_id',$this->away_coach_id);
		$criteria->compare('confirm',$this->confirm);
		$criteria->compare('stage',$this->stage);
		$criteria->compare('group',$this->group);
		$criteria->compare('career',$this->career);
		$criteria->compare('enable',$this->enable);
		$criteria->compare('rating_change',$this->rating_change);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}