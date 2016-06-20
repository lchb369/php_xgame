<?php
class User_Exception extends GameException
{
	
	/**
	 * 用户不存在
	 * @var	int
	 */
	const STATUS_USER_NOT_EXIST = 201;
	
	/**
	 * 用户已经存在
	 * @var	int
	 */
	const STATUS_USER_EXIST = 202;
	
	/**
	 * 金币不够
	 * @var	int
	 */
	const STATUS_NOT_ENOUGH_GOLD = 203;
	
	/**
	 * 充值币不够
	 * @var	int
	 */
	const STATUS_NOT_ENOUGH_COIN = 204;

	/**
	 * 好友数达到上限
	 * @var	int
	 */
	const STATUS_FRIEND_MAX = 205;
	
	/**
	 * 体力值不够
	 */
	const STATUS_NOT_ENOUGH_STAMINA = 206;
	
	/**
	 * 援军点数不够
	 */
	const STATUS_NOT_ENOUGH_GACHA = 207;
	
	/**
	 * 昵称已经被使用
	 */
	const STATUS_NICKNAME_USED = 208;
	
	/**
	 * 不能领取
	 */
	const STATUS_CANNOT_GET_REWARD = 209;
	
	/**
	 * 不在活动时间内
	 * @var unknown
	 */
	const STATUS_NOT_IN_ACTIVITY_TIME = 210;
	
	/**
	 * 邀请码无效
	 */
	const STATUS_INVITE_CODE_INVALID = 211;
	
	/**
	 * 已经领取
	 */
	const STATUS_ALREADY_GET_REWARD = 212;
	
	
	/**
	 * 輸入的序號有誤
	 */
	const STATUS_ACTIVE_CODE_INVALID = 213;
	
	/**
	 * 帐号已经绑定
	 */
	const STATUS_LOGIN_NAME_HAS_BIND = 214;
	
	/**
	 * 搜索好友传值为空
	 */
	const STATUS_FRIEND_SEARCH_EMPTY = 215;
	
	/**
	 * 不能加自己为好友
	 */
	const STATUS_FRIEND_SEARCH_MYSELF = 216;
	
	/**
	 * 此組序號已兌換過
	 */
	const STATUS_ACTIVE_CODE_USED = 217;
	
	/**
	 * 每个账号只能激活一次。
	 * 您已經兌換過了唷！
	 */
	const STATUS_ACTIVE_CODE_ONE_TIME_FOR_ONE_ACCOUNT = 218;
	
	/**
	 * 对方好友数达到上限
	 * @var	int
	 */
	const STATUS_OPPOSITE_FRIEND_MAX = 219;
	
	/**
	 * 不存在这个道具配置
	 * @var unknown
	 */
	const STATUS_NO_ITEM_CONFIG = 220;
	
	/**
	 * 领取体力值失败
	 */
	const STATUS_RECOVERY_STAMINA_FAIL = 221;
	
	//昵称不能为空
	const STATUS_NICKNAME_EMPTY = 230;
	
	
	//昵称字符长度不合法
	const STATUS_NICKNAME_INVALID = 231;
	
	
	//昵称重复
	const STATUS_NICKNAME_REPEAT = 232;
	
	//重置创建角色
	const STATUS_CREATE_REPEAT_ID = 233;
}

?>