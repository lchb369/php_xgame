<?php

/**
 * 道具模块
 * @name Info.php
 * @author Liuchangbing
 * @since 2013-1-14
 */
class Item_Model
{
	
	protected $userId;
	/**
	 * 单例对象
	 * @var	User_Info[]
	 */
	protected static $singletonObjects;
	
	
	/**
	 * 结构化对象
	 * @param	int $userId	用户ID
	 * @param	boolean $lock
	 */
	public function __construct( $userId )
	{
		$this->userId = $userId;
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	User_Info
	 */
	public static function & getInstance( $userId )
	{
		if( !isset( self::$singletonObjects[$userId] ) )
		{
			self::$singletonObjects[$userId] = new self( $userId );
		}
		return self::$singletonObjects[$userId];
	}
	
	/**
	 * 改变道具数量
	 * @param unknown $itemCid
	 * @param unknown $num
	 */
	public function changeItem( $itemCid , $num )
	{
		$itemConfig = Common::getConfig( 'items' );
		if( !$itemConfig[$itemCid])
		{
		//	throw new Equip_Exception( Equip_Exception::STATUS_CONFIG_NOT_EXSIT );
		}
		return Data_Item_Model::getInstance( $this->userId , true )->changeItem( $itemCid , $num );
		
	}
	
	/**
	 * 出售道具
	 * @param unknown $itemId
	 * @param number $num
	 * @throws Item_Exception
	 */
	public function sell( $itemId , $num = 1 )
	{
		$itemConfig = Common::getConfig( 'items' );
		$itemConf = $itemConfig[$itemId];
		if( empty( $itemConf ))
		{
			throw new Item_Exception( Item_Exception::STATUS_ITEM_CONF_NOT_EXIST );
		}
		
		//TODO::要增加配置
		$sellGold = $itemConf['sell_gold'] ? $itemConf['sell_gold'] : 100;
		
		User_Info::getInstance( $this->userId )->changeGold( $sellGold );
		
		$status = Data_Item_Model::getInstance( $this->userId , true )->changeItem( $itemId , -$num );
		
		if( $status == false )
		{
			throw new Item_Exception( Item_Exception::STATUS_ITEM_NOT_ENOUGH );
		}
		
	}
	
	/**
	 * 使用道具
	 * @param unknown $itemId
	 */
	public function useItem( $itemId )
	{
		$itemConfig = Common::getConfig( 'items' );
		$itemConf = $itemConfig[$itemId];
		if( empty( $itemConf ))
		{
			throw new Item_Exception( Item_Exception::STATUS_ITEM_CONF_NOT_EXIST );
		}
		
		if( !$itemConf['can_use'] )
		{
			throw new Item_Exception( Item_Exception::STATUS_ITEM_CONF_NOT_EXIST );
		}
		
		//如果对应一个掉落包
		if( $itemConf['effect_attr'] == 'package' && $itemConf['effect_id'] > 0 )
		{
			return $this->usePackage( $itemConf['effect_id'] );
		}
		return  null;
	}
	
	/**
	 * 使用掉落包
	 */
	public function usePackage( $packageId )
	{
		$packageConfig = Common::getConfig( 'package' );
		$packConf = $packageConfig[$packageId];
		if( empty( $packConf ) )
		{
			throw new Item_Exception( Item_Exception::STATUS_ITEM_CONF_NOT_EXIST );
		}
		
		//判断是否会掉落
		$randNum = mt_rand( 1 , 10000 );
		if( $randNum <= $packConf['drop_rate'])
		{
			return null;
		}
		
		$dropArr = null;
		//判断会掉些什么
		for ( $i = 1 ; $i < 10 ; $i++ )
		{
			$dropInfo = null;
			$randNum = mt_rand( 1 , 10000 );
			$dropWeight = $packConf['weight'.$i];
			if( $randNum < $dropWeight )
			{
				continue;
			}
			
			//掉落类型
			$dropType = $packConf['item_type'.$i];
			if( !$dropType )
			{
				break;
			}
			
			//如果是多选一掉落,分普通随机掉落和按权重掉落
			if( preg_match( "/|/", $packConf['max_num'.$i] ))
			{
				$itemIds = explode( "|", $packConf['item_id'.$i]);
				
				//按权重选取
				if(  $packConf['rand_weight'.$i] )
				{
					$randWeight = explode( "|" , $packConf['rand_weight'.$i] );
					$index = self::randByWeight( $randWeight );
				}
				//平均随机
				else 
				{
					$index = array_rand( $itemIds );
				}
				
				//获取掉落ID
				$dropId = $itemIds[$index];
				
				//掉落数量
				$minNumArr = explode( "|" , $packConf['min_num'.$i]);
				$maxNumArr = explode( "|" , $packConf['max_num'.$i]);
				
				//掉落个数
				$dropNum = mt_rand( $minNumArr[$index] , $maxNumArr[$index] );
				
				if( !$dropId || !$dropNum )
				{
					throw new Item_Exception( Item_Exception::STATUS_ITEM_CONF_NOT_EXIST );
				}
				
			}
			else 
			{
				$dropId = $packConf['item_id'.$i];
				$dropMinNum = $packConf['min_num'.$i];
				$dropMaxNum = $packConf['max_num'.$i];
				$dropNum = mt_rand( $dropMinNum , $dropMaxNum );	
			}
			
			
			//增加普通道具
			if( $dropType == 'item' &&  $dropId > 0 && $dropNum > 0 )
			{
				$dropInfo['item']['id'] = $dropId;
				$dropInfo['item']['num'] = $dropNum;
				$this->changeItem( $dropId ,  $dropNum );
			}
			elseif( $dropType == 'equip' && $dropId > 0 ) 
			{
				Equip_Model::getInstance( $this->userId)->addEquip( $dropId );
				$dropInfo['equip']['id'] = $dropId;
				$dropInfo['equip']['num'] = 1;
			}
			elseif( $dropType == 'coin' && $dropNum > 0 )
			{
				User_Info::getInstance( $this->userId )->changeCoin( $dropNum );
				$dropInfo['coin'] = $dropNum;
			}
			elseif( $dropType == 'gold' && $dropNum > 0 )
			{
				User_Info::getInstance( $this->userId )->changeGold( $dropNum );
				$dropInfo['gold'] = $dropNum;
			}
			elseif( $dropType == 'exp' && $dropNum > 0 )
			{
				User_Info::getInstance( $this->userId )->changeExp( $dropNum );
				$dropInfo['exp'] = $dropNum;
			}
			elseif( $dropType == 'strength' && $dropNum > 0 )
			{
				User_Info::getInstance( $this->userId )->addStrength( $dropNum );
				$dropInfo['strength'] = $dropNum;
			}
			elseif( $dropType == 'vigor' && $dropNum > 0 )
			{
				User_Info::getInstance( $this->userId )->addVigor( $dropNum );
				$dropInfo['vigor'] = $dropNum;
			}
			elseif( $dropType == 'skill' )
			{
				Data_Hero_PassiveSkill::getInstance( $this->userId , true )->add( $dropId , 1 );
				$dropInfo['skill'] = $dropId;
			}
			elseif( $dropType == 'hero' )
			{
				Hero_Model::getInstance( $this->userId )->addHero( $dropId );	
				$dropInfo['hero'] = $dropId;
			}
		
			if( $dropInfo )
			{
				$dropArr[] = $dropInfo;
			}
		}	
		return $dropArr;
	}
	
	/**
	 * 随机数
	 * @param unknown $randWeight
	 * @return unknown|number
	 */
	public static function randByWeight( $randWeight )
	{
		$randNum = mt_rand( 1 , 10000 );
		$index = 0;
		foreach ( $randWeight as $key => $weight )
		{
			if( $randNum <= $weight )
			{
				return $key;
			}
		}
		return $index;
	}
	
	
	//获取道具列表
	public function getData()
	{
		return Data_Item_Model::getInstance( $this->userId )->getData();
	}
	
}
