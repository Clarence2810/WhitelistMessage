<?php

namespace Clarence2810\WhitelistMessage;

use pocketmine\{event\Listener, event\player\PlayerLoginEvent, plugin\PluginBase, Server, utils\Textformat as C};

class Main extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPreLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        if (!Server::getInstance()->isWhitelisted($player->getName())) {
            if ($this->getConfig()->get("whitelist-notify") === true) {
                foreach ($this->getServer()->getOnlinePlayers() as $players) {
                    if ($players->hasPermission("whitelistmessage.alert") || Server::getInstance()->isWhitelisted($players->getName())) {
                        $players->sendMessage(str_replace(["{player}", "&"], [$player->getName(), C::ESCAPE], $this->getConfig()->get("whitelist-notify-message")));
                    }
                }
            }
            $event->setKickMessage(str_replace("&", C::ESCAPE, $this->getConfig()->get("kick-message")));
            $event->cancel();
        }
    }
}