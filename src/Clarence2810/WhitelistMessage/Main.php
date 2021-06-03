<?php

namespace Clarence2810\WhitelistMessage;

use pocketmine\{
	Player,
	event\Listener,
	event\player\PlayerPreLoginEvent,
	plugin\PluginBase,
	utils\Textformat as C,
};
class Main extends PluginBase implements Listener
{
	public function onEnable(){
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onPreLogin(PlayerPreLoginEvent $event):void{
		$player = $event->getPlayer();
		if(!$player->isWhitelisted()){
			if($this->getConfig()->get("whitelist-notify") === true) foreach($this->getServer()->getOnlinePlayers() as $players) if($players->hasPermission("whitelistmessage.alert") || $players->isWhitelisted()) $players->sendMessage(str_replace(["{player}", "&"], [$player->getName(), C::ESCAPE], $this->getConfig()->get("whitelist-notify-message")));
			$event->setKickMessage(str_replace("&", C::ESCAPE, $this->getConfig()->get("kick-message")));
			$event->setCancelled();
		}
	}
}