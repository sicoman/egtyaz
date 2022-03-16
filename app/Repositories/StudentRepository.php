<?php

namespace App\Repositories;

 

use DB;


use Auth;

use Mail;

use View;

use App\Questions;

use App\Models\examAnswers;

use App\Models\Exams as Exam;

use App\Repositories\BaseRepository;

use App\Services\TaxonomyService as Taxonomy;

use App\Repositories\ExamsRepository as Exams;

use App\Repositories\PostsRepository as Posts;
use Illuminate\Container\Container as Application;

/**
 * Interface ChallengeRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class StudentRepository extends BaseRepository
{

    protected $taxonomy;

    protected $posts;

    public function __construct(Application $app, Exams $exams, Taxonomy $taxonomy, Posts $posts)
    {
        parent::__construct($app);

        $this->posts = $posts;

        $this->exams = $exams;

        $this->taxonomy = $taxonomy;

    }

    public function model()
    {
        return ('App\\Models\Options');
    }

    public function getSetting($type)
    {
        if (is_array($type)) {
            $returnOptions = [];
            $options = $this->model->whereIn('type', $type)->select(['type', 'option_value', 'option_var']);
            foreach ($options as $option) {
                $returnOptions[$option->type][$option->option_var] = $option->option_value;
            }
            return $returnOptions;
        }
        return $this->model->where('type', $type)->pluck('option_value', 'option_var');
    }

    public function exam()
    {
        return $this->taxonomy->getSubjectsObject()->with('childrens:id,name,parent')->get();
    }

    public function makeExam($data)
    {

        $time = $data['time'] * 60;
        $count = $data['count'];

        if (empty($data['subjects'])) {
            return back();
        }


        $subjects = array_keys($data['subjects']);
        $skills = [];


        foreach ($data['subjects'] as $skill) {
            foreach ($skill['skills'] as $k => $sk) {
                $skills[] = $k;
            }
        }

        return $this->exams->CreateExam('free', Auth::user()->id, $subjects, $skills, $time, $count);
    }

    public function getExam($id, $limit = 0)
    {


        $exam = $this->exams->model->where('id', $id)->with('subjects')->with('skills')->with('Answers')->first();

        if (!isset($exam->id)) {
            return false;
        }

        $subjects = [];
        $skills = [];
        foreach ($exam->subjects as $subject) {
            $subjects[] = $subject->subject_id;
        }
        foreach ($exam->skills as $skill) {
            $skills[] = $skill->skill_id;
        }


        $questions = $this->exams->getExamQuestionsObject($id,$exam, $skills, $subjects, $exam->questions_count)
            ->with('answers')->with('attachment')->get();

        return ['exam' => $exam, 'questions' => $questions, 'subjects' => $subjects, 'skills' => $skills];

    }

    public function getExamResult($id, $limit = 0, $forceUser = 0)
    {

        $exam = $this->exams->model->where('id', $id)->with('subjects')->with('skills')->first();

        if (!isset($exam->id)) {
            return false;
        }

        if ($forceUser == 0) {
            $forceUser = auth()->user()->id ?? 0;
        }

        $results = $exam->results()->where('user_id', $forceUser)->orderBy('id', 'DESC')->first();

        $answers = $exam->answers()->where('student_id', $forceUser)->groupBy('question_id')->with('question')->paginate($limit);

        $questions = $exam->questions()->groupBy('question_id')->with('question')->paginate($limit);

        return ['exam' => $exam, 'answers' => $answers, 'results' => $results, 'questions' => $questions];

    }

    public function startExam($examId)
    {
        return $this->getExam($examId);
    }

    public function saveExam($examId, $answers)
    {
        $exam = $this->exams->model->find($examId);

        $questions_list = [];

        // Lets delete all exam result prev for this user
      $delete_results =   DB::table('exams_results')->where('exam_id', $examId)->where('user_id', auth()->user()->id)->delete();
      $delete_answers =   DB::table('exams_answers')->where('exam_id', $examId)->where('student_id', auth()->user()->id)->delete();


//        if ($exam->type == 'free') {
//            DB::table('exams_questions')->where('exam_id', $examId)->delete();
//        }

        // Lets get the result
        $all_counts = $exam->questions_count;
        $results = [
            'exam_id' => $exam->id,
            'user_id' => auth()->user()->id,
            'valid_answers' => 0,
            'wrong_answers' => 0,
            'percent' => 0,
        ];

        $student_id = auth()->user()->id;

        foreach ($answers['answers'] as $answer) {
            $answer = (array)$answer;
            $tru = 0;
            if ($answer['is_true'] == 'true' || $answer['is_true'] == 1) {
                $tru = 1;
            } elseif ($answer['is_true'] == -1) {
                $tru = -1;
            }
            $data = [
                'student_id' => $student_id,
                'question_id' => $answer['id'],
                'answer_id' => $answer['answer'],
                'is_true' => $tru,
                'spent_time' => $answer['time']
            ];

            $questions_list[] = $answer['id'];

            $exam->answers()->create($data);

            if ((int)$data['is_true'] == 1) {
                $results['valid_answers']++;
            }
        }

        if ($exam->type == 'free') {
            foreach ($questions_list as $question) {
                $exam->questions()->create([
                    'exam_id' => $examId,
                    'question_id' => $question
                ]);
            }
        }


        $results['wrong_answers'] = $all_counts - $results['valid_answers'];

        $results['percent'] = round((($results['valid_answers'] * 100) / $all_counts), 1);


        $exam->results()->create($results);

        return $examId;

    }

    public function getExamsByType($type = 'free', $student_id = null)
    {
        $exam = $this->exams->where('type', $type);
        if ($type == 'free') {
            if ($student_id) {
                $exam = $exam->where('student_id', $student_id);
            }
            return $exam->orderBy('id', 'DESC')->paginate(10);
        } elseif ($type == 'challenge') {
            return $exam->orderBy('id', 'DESC')->paginate(10);
        } else {
            return $exam->orderBy('id', 'ASC')->paginate(10);
        }
    }

    public function getExamsByParents($parent = 0, $type = 'challenge', $limit = 10)
    {
        return $this->exams->where('type', $type)->where('parent', $parent)->orderBy('id', 'DESC')->paginate($limit);
    }

    public function getQuestion($id, $limit = 0)
    {
        $question = Questions::where('id', $id)->with('answers')->with('attachment')->get();
        if (!isset($question)) {
            return false;
        }
        return ['question' => $question];
    }

    public function getRate()
    {
        $list = [
            'subjects' => [],
            'skills' => [],
            'questions' => []
        ];

        // get student mock tests
        $mock_ids = $this->exams->where('type', 'mock')->pluck('id')->toArray();

        // Get All User Answered Questions
        $answers = examAnswers::where('student_id', auth()->user()->id)
            //->whereIn('exam_id' , $mock_ids )
            ->select(['id', 'question_id', 'is_true', DB::raw('count(id) as times')])
            ->groupBy('question_id')->groupBy('is_true')->with('question:id,category_id,skill_id,subject_id')->get();

        foreach ($answers as $answer) {
            $list['questions'][$answer->question_id][$answer->is_true] = $answer->times;
            $list['subjects'][$answer->question->subject_id ?? 0][$answer->question_id] = $answer->question_id;
            $list['skills'][$answer->question->skill_id ?? 0][$answer->question_id] = $answer->question_id;
        }

        $percents = [];

        foreach ($list['subjects'] as $subject => $data) {
            $percents[$subject] = $this->getPercent($data, $list['questions']);
        }

        foreach ($list['skills'] as $skill => $data) {
            $percents[$skill] = $this->getPercent($data, $list['questions']);
        }

        // Get This Taxonomies

        $rateTaxs = $this->taxonomy->WhereIn('id', array_keys($percents))->select(['id', 'type', 'name', 'parent'])->get()->toArray();

        return ['taxonomies' => $rateTaxs, 'percents' => $percents];
    }

    public function getPercent($list, $questions = [])
    {
        $return = [
            'times' => 0,
            'is_true' => 0,
            'is_false' => 0,
            'percent' => 0
        ];
        foreach ($list as $question) {
            if (isset($questions[$question][0])) {
                $return['is_false'] = $return['is_false'] + $questions[$question][0];
            }

            if (isset($questions[$question][1])) {
                $return['is_true'] = $return['is_true'] + $questions[$question][1];
            }
        }

        $return['times'] = $return['is_false'] + $return['is_true'];

        if ($return['times'] == 0) {
            $return['times'] = 1;
        }

        $return['percent'] = round(($return['is_true'] * 100) / $return['times'], 1);

        return $return;

    }


    

    public function getExamsBySubjects($id , $student_id = null)
    {
        $exam = Exam::whereHas('Subjects', function ($query) use($id) {
            if ($id == 2){ 
                $query->whereIn('subject_id' , ['23', '24']);
            }else{ 
                $query->where('subject_id', $id);
            }
        });
        // if ($student_id) {
        //     $exam = $exam->where('student_id', $student_id);
        // }
        return $exam->orderBy('id', 'DESC')->paginate(10);
  
    }

}
