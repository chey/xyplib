<?php
namespace Xymon\Message;

interface MessageInterface
{
    const DEFAULT_SYNTAX = Syntax\DefaultSyntax::class;

    public function command();
    public function data();
    public function syntax();
    public function __toString();
}
