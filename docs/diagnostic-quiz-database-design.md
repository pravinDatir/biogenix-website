# Diagnostic Quiz Minimal Required Schema

## Simple but complete

For the existing flow, we should still store:

- quiz questions
- answer choices
- participant response
- selected answers

To keep it simple, we should not add extra campaign, admin, or analytics tables.

## Required tables

### `diagnostic_quiz_questions`

Stores the question text shown in the quiz.

| Column | Type |
| --- | --- |
| `id` | bigint unsigned PK |
| `question_text` | text |
| `display_order` | unsignedTinyInteger |
| `created_at` / `updated_at` | timestamps |

### `diagnostic_quiz_answer_options`

Stores the answer choices for each question.

| Column | Type |
| --- | --- |
| `id` | bigint unsigned PK |
| `question_id` | foreignId |
| `option_label` | string(5) |
| `option_text` | string(255) |
| `is_correct_answer` | boolean |
| `display_order` | unsignedTinyInteger |
| `created_at` / `updated_at` | timestamps |

### `diagnostic_quiz_responses`

Stores the participant details and final score.

| Column | Type |
| --- | --- |
| `id` | bigint unsigned PK |
| `participant_first_name` | string(100) |
| `participant_last_name` | string(100) nullable |
| `participant_email` | string(150) |
| `total_correct_answers` | unsignedTinyInteger |
| `score_percentage` | unsignedTinyInteger |
| `reward_coupon_code` | string(50) nullable |
| `submitted_at` | timestamp |
| `created_at` / `updated_at` | timestamps |

### `diagnostic_quiz_response_answers`

Stores which answer was selected for each question in one quiz response.

| Column | Type |
| --- | --- |
| `id` | bigint unsigned PK |
| `response_id` | foreignId |
| `question_id` | foreignId |
| `selected_option_id` | foreignId |
| `is_correct_answer` | boolean |
| `created_at` / `updated_at` | timestamps |

## Where do we store questions and answers

- Questions go in `diagnostic_quiz_questions`
- Answer choices go in `diagnostic_quiz_answer_options`
- Final participant record goes in `diagnostic_quiz_responses`
- Selected answers go in `diagnostic_quiz_response_answers`

## Coupon handling

Keep using the existing `coupons` table for `BIOGENIX15`.

Only store the issued code in `diagnostic_quiz_responses.reward_coupon_code`.

## Final recommendation

Use only these 4 tables:

- `diagnostic_quiz_questions`
- `diagnostic_quiz_answer_options`
- `diagnostic_quiz_responses`
- `diagnostic_quiz_response_answers`

This stays simple without removing required storage.
