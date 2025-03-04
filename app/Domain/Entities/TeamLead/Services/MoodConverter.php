<?php

namespace App\Domain\Entities\TeamLead\Services;

use App\Domain\Entities\TeamLead\Enums\MoodStateEnum;
use App\Domain\Entities\TeamLead\Enums\WorkStateEnum;
use App\Domain\Entities\TeamLead\ValueObjects\WorkResponse;
use InvalidArgumentException;

class MoodConverter
{
    private array $matrix = [];

    public function __construct(?array $matrix = null)
    {
        if (!$matrix) {
            $this->matrix[MoodStateEnum::GOOD_MOOD->value] = [
                WorkStateEnum::SUCCESS->value => new WorkResponse(
                    MoodStateEnum::GOOD_MOOD,
                    'You are doing your best! Respect!'
                ),
                WorkStateEnum::FAILED->value => new WorkResponse(
                    MoodStateEnum::NORMAL_MOOD,
                    'Next time you will be better, try harder!'
                ),
            ];

            $this->matrix[MoodStateEnum::NORMAL_MOOD->value] = [
                WorkStateEnum::SUCCESS->value => new WorkResponse(
                    MoodStateEnum::GOOD_MOOD,
                    'I told you it would work! Good!'
                ),
                WorkStateEnum::FAILED->value =>  new WorkResponse(
                    MoodStateEnum::BAD_MOOD,
                    'Hmm... Come back tomorrow and redo everything.'
                ),
            ];

            $this->matrix[MoodStateEnum::BAD_MOOD->value] = [
                WorkStateEnum::SUCCESS->value => new WorkResponse(
                    MoodStateEnum::NORMAL_MOOD,
                    'It\'s better now, because I know you can.'
                ),
                WorkStateEnum::FAILED->value =>  new WorkResponse(
                    MoodStateEnum::ANGRY_MOOD,
                    'Hide so that no one sees you!'
                ),
            ];

            $this->matrix[MoodStateEnum::ANGRY_MOOD->value] = [
                WorkStateEnum::SUCCESS->value => new WorkResponse(
                    MoodStateEnum::BAD_MOOD,
                    'Are you still here? Go back to work.'
                ),
                WorkStateEnum::FAILED->value =>  new WorkResponse(
                    MoodStateEnum::ANGRY_MOOD,
                    'OMG! I\'m murder killer! You will be fired!'
                ),
            ];
        } else {
            $this->matrix = $matrix;
        }

    }

    public function convert(MoodStateEnum $mood, WorkStateEnum $workState): WorkResponse
    {
        if (isset($this->matrix[$mood->value]) && isset($this->matrix[$mood->value][$workState->value])) {
            return $this->matrix[$mood->value][$workState->value];
        }

        throw new InvalidArgumentException(
            "Converter matrix is not defined for mood state:'{$mood->name}' and work state: '$workState->name'"
        );
    }
}