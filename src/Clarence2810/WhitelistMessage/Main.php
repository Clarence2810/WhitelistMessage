<?php

namespace Clarence2810\WhitelistMessage;

use pocketmine\{event\Listener,
    event\player\PlayerPreLoginEvent,
    event\server\DataPacketSendEvent,
    network\mcpe\protocol\DisconnectPacket,
    plugin\PluginBase,
    Server,
    utils\Textformat as C
};

class Main extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onKick(DataPacketSendEvent $event): void
    {
        $packets = $event->getPackets();
        foreach ($packets as $packet) {
            if ($packet instanceof DisconnectPacket && $packet->message === "Server is whitelisted") {
                $packet->message = str_replace("&", C::ESCAPE, $this->getConfig()->get("kick-message"));
            }
        }
    }

    public function onPreLogin(PlayerPreLoginEvent $event): void
    {
        if (!Server::getInstance()->isWhitelisted($event->getPlayerInfo()->getUsername()) && $this->getConfig()->get("whitelist-notify") === true) {
            foreach ($this->getServer()->getOnlinePlayers() as $players) {
                if ($players->hasPermission("whitelistmessage.alert") || Server::getInstance()->isWhitelisted($players->getName())) {
                    $players->sendMessage(str_replace(["{player}", "&"], [$event->getPlayerInfo()->getUsername(), C::ESCAPE], $this->getConfig()->get("whitelist-notify-message")));
                }
            }
        }
    }
}