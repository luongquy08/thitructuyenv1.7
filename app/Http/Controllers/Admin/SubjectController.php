<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectAddRequest;
use App\Http\Requests\SubjectEditRequest;
use App\Models\Subject;
use DateTime;

class SubjectController extends Controller
{
    public function getSubjectAdd(){
        return view('admin.module.subject.add');
    }

    public function postSubjectAdd(SubjectAddRequest $request){
        $subject = new Subject;
        $subject->name = $request->txtSubjectName;
        $subject->created_at = new DateTime();
        $subject->save();
        return redirect()->route('getSubjectList')->with(['flash_level'=>'result_msg', 'flash_message'=>'Thêm môn học thành công']);
    }

    public function getSubjectList(){
        $subjectsData = Subject::select('id', 'name', 'created_at')->get()->toArray();
        return view('admin.module.subject.list', ['subjectsData'=>$subjectsData]);
    }

    public function getSubjectDel($id){
        $subject_del = Subject::find($id);
        $subject_del->delete();
        return redirect()->route('getSubjectList')->with(['flash_level'=>'result_msg', 'flash_message'=>'Xóa môn học thành công']);
    }

    public function getSubjectEdit($id){
        $subjectData = Subject::findOrFail($id);
        return view('admin.module.subject.edit', ['subjectData'=>$subjectData]);
    }

    public function postSubjectEdit(SubjectEditRequest $request, $id){
        $subject = Subject::findOrFail($id);
        if($subject->name != $request->txtSubjectName){
            $this->validate($request,
                [
                    'txtSubjectName'=>'unique:uet_subjects,name'
                ],
                [
                    'txtSubjectName.unique'=>'Môn học này đã tồn tại, vui lòng nhập tên khác'
                ]
            );
        }
        $subject->name = $request->txtSubjectName;
        $subject->updated_at = new DateTime();
        $subject->save();
        return redirect()->route('getSubjectList')->with(['flash_level'=>'result_msg', 'flash_message'=>'Sửa môn học thành công']);
    }
}
