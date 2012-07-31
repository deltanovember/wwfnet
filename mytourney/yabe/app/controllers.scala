package controllers

import play._
import play.mvc._
import models._
import play.data.validation._
import play.libs._
import play.cache._

object Application extends Controller {

  import views.Application._
  def captcha(id:String) = {
    val captcha = Images.captcha
    val code = captcha.getText("#E4EAFD")
    Cache.set(id, code, "10mn")
    captcha
  }

  def index = {
    val allPosts = Post.allWithAuthorAndComments
    html.index(
      front = allPosts.headOption,
      older = allPosts.drop(1)
    )
  }

  def show(id: Long) = {
    Post.byIdWithAuthorAndComments(id).map { post =>
      html.show(post, post._1.prevNext)
    } getOrElse {
      NotFound("No such Post")
    }
  }


  de postComment(postId:Long) = {
    val author = params.get("author")
    val content = params.get("content")
    Validation.required("author", author)
    Validation.required("content", content)
    if(Validation.hasErrors) {
      show(postId)
    } else {
      Comment.create(Comment(postId, author, content))
      flash += "success" -> ("Thanks for posting " + author)
      Action(show(postId))
    }
  }



}