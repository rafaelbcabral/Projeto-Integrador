<?php

class DominioException extends RuntimeException
{

    private array $problemas = [];

    public function setProblemas(array $problemas): self
    {
        $this->problemas = $problemas;
        return $this;
    }

    public function getProblemas(): array
    {
        return $this->problemas;
    }
}
