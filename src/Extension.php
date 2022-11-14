<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface as LeagueExtensionInterface;

class Extension implements LeagueExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment) : void
    {
        $environment->addBlockStartParser(
            ImplicitFiguresBlockParser::createBlockStartParser()
        );
        $environment->addRenderer(
            ImplicitFigures::class, new ImplicitFiguresRenderer(), 0
        );
        $environment->addRenderer(
            FigCaption::class, new FigCaptionRenderer(), 0
        );
    }
}
