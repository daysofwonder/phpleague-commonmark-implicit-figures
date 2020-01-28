<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface as LeagueExtensionInterface;

class Extension implements LeagueExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment->addBlockParser(
            new ImplicitFiguresBlockParser()
        );
        $environment->addBlockRenderer(
            ImplicitFigures::class, new ImplicitFiguresRenderer(), 0
        );
        $environment->addInlineRenderer(
            FigCaption::class, new FigCaptionRenderer(), 0
        );
    }
}
