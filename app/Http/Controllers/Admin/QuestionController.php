<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\QuestionAddRequest;
use App\Http\Requests\QuestionEditRequest;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Test;
use DateTime;

class QuestionController extends Controller
{
    public function getQuestionAdd(){
        $questionsData = Question::select('id', 'content', 'created_at')->get()->toArray();
        $testsData = Test::select('id', 'name', 'created_at')->orderBy('id', 'DESC')->get()->toArray();
        return view('admin.module.question.add', ['questionsData'=>$questionsData, 'testsData'=>$testsData, 'oldSelectTest'=>'']);
    }

    public function postQuestionAdd(QuestionAddRequest $request){
        $question = new Question;
        $question->content = $request->txtQuestion;
        $question->test_id = $request->sltTest;
        $question->correct_answer = $request->rdoCorrectAnswer;
        $question->created_at = new DateTime();
        $question->save();

        for ($i = 1; $i <= count($request->txtAnswer); $i++) {
            $answer = new Answer;
            $answer->question_id  = $question->id;
            $answer->content = $request->txtAnswer[$i];
            $answer->created_at = new DateTime();
            $answer->save();
            if($i == $request->rdoCorrectAnswer){
                $question->correct_answer = $answer->id;
                $question->save();
            }
        }
        $testsData = Test::select('id', 'name')->orderBy('id', 'DESC')->get()->toArray();
        $questionsData = Question::select('id', 'content', 'created_at')->get()->toArray();
        $old = $request->sltTest;
        return view('admin.module.question.add', ['questionsData'=>$questionsData, 'testsData'=>$testsData, 'oldSelectTest'=>$old, 'flash_level'=>'result_msg', 'flash_message'=>'Thêm câu hỏi thành công']);
    }

    public function getQuestionDel($id){
        $question = Question::findOrFail($id);
        $question->delete($id);
        $testsData = Test::select('id', 'name')->get()->toArray();
        $questionsData = Question::select('id', 'content', 'created_at')->get()->toArray();
        return redirect()->route('getQuestionAdd')->with(['questionsData'=>$questionsData, 'testsData'=>$testsData, 'flash_level'=>'result_msg', 'flash_message'=>'Xóa câu hỏi thành công']);
    }

    public function getQuestionEdit($id){
        $questionEditData = Question::findOrFail($id);
        $questionsData = Question::select('id', 'content', 'created_at')->get()->toArray();
        $testsData = Test::select('id', 'name')->orderBy('id', 'DESC')->get()->toArray();
        $answersData = Answer::select('id', 'content')->where('question_id', $id)->get()->toArray();

        return view('admin.module.question.edit', ['questionsData'=>$questionsData, 'testsData'=>$testsData, 'questionEditData'=>$questionEditData, 'answersData'=>$answersData]);
    }

    public function postQuestionEdit(QuestionEditRequest $request, $id){
        $question = Question::findOrFail($id);
        $question->content = $request->txtQuestion;
        $question->test_id = $request->sltTest;
        $question->correct_answer = $request->rdoCorrectAnswer;
        $question->updated_at = new DateTime();
        $question->save();

        $arrayAnswer = $request->txtAnswer;

        foreach ($arrayAnswer as $key => $value){
            $answer = Answer::findOrFail($key);
            $answer->content = $value;
            $answer->updated_at = new DateTime();
            $answer->save();
        }
        $testsData = Test::select('id', 'name')->get()->toArray();
        $questionsData = Question::select('id', 'content', 'created_at')->get()->toArray();
        $old = $request->sltTest;
        return redirect()->route('getQuestionAdd')->with(['questionsData'=>$questionsData, 'testsData'=>$testsData, 'oldSelectTest'=>$old, 'flash_level'=>'result_msg', 'flash_message'=>'Cập nhật câu hỏi thành công']);
    }
    
    public function getQuestionList($id){
        $questionsData = Question::select('id', 'content', 'created_at')->where('test_id', $id)->get()->toArray();
        $testsData = Test::findOrFail($id);
        return view('admin.module.question.list', ['questionsData'=>$questionsData, 'testsData'=>$testsData]);
    }
}