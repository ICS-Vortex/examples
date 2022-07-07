<?php


namespace App\Service\ED\Versions;


use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class EDVersionsService
{
    public const CHANNEL_STABLE = 'stable';
    public const CHANNEL_BETA = 'beta';
    public static $betaChannelUrl = 'https://www.digitalcombatsimulator.com/en/news/changelog/openbeta/';
    public static $stableChannelUrl = 'https://www.digitalcombatsimulator.com/en/news/changelog/stable/';
    private array $versions = [];

    public function getLatestBetaVersion()
    {
        $this->crawlUpdatesChannel(self::CHANNEL_BETA);
        return $this->versions['beta'][0] ?? null;
    }

    public function getLatestStableVersion()
    {
        $this->crawlUpdatesChannel(self::CHANNEL_STABLE);
        return $this->versions['stable'][0] ?? null;
    }

    private function crawlUpdatesChannel($channel = self::CHANNEL_BETA): void
    {
        $url = match ($channel) {
            self::CHANNEL_BETA => self::$betaChannelUrl,
            self::CHANNEL_STABLE => self::$stableChannelUrl,
        };
        $converter = new CssSelectorConverter();
        $html = file_get_contents($url);
        $crawler = new Crawler($html);
        $divContent = $crawler->filterXPath($converter->toXPath('div#changelog-sidebar > div > div > div.row'));
        $firstNode = $divContent->getNode(0);
        if (empty($firstNode)) return;
        $versionsString = trim(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $firstNode->textContent));
        $l = 0;
        $versions = [];
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $versionsString) as $line) {
            if ($l === 0) { // Skip first line
                $l++;
                continue;
            }
            $line = trim($line);
            $versionInfoArray = explode(' ', $line);
            $versions[] = $versionInfoArray[1] ?? null;
        }
        $this->versions[$channel] = $versions;
    }
}