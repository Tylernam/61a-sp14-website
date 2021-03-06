<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
  <head>
    <meta name="description" content ="CS61A: Structure and Interpretation of Computer Programs" /> 
    <meta name="keywords" content ="CS61A, Computer Science, CS, 61A, Programming, John DeNero, Berkeley, EECS" />
    <meta name="author" content ="Paul Hilfinger, Soumya Basu, Rohan Chitnis, Andrew Huang, Robert Huang, Michelle Hwang,
                                  Joy Jeng, Keegan Mann, Mark Miyashita, Allen Nguyen, Julia Oh, Steven Tang, Albert Wu, Chenyang Yuan, Marvin Zhang" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <style type="text/css">@import url("../lab_style.css");</style>
    <style type="text/css">@import url("../61a_style.css");</style>

    <title>CS 61A Spring 2014: Lab 8</title> 

    <?php
    /* So all of the PHP in this file is to allow for this nice little trick to 
     * help us avoid having two versions of the questions lying around in the 
     * repository, which often leads to the two versions going out of sync which 
     * leads to annoyance for students.
     *
     * The idea's pretty simple for the PHP part, just simply have two dates: 
     *
     *    1. The current date
     *    2. The date the solutions should be released
     *
     * Using these, we now wrap our solutions in a simple PHP if statement that 
     * checks if the date is past the release date and only includes the code on 
     * the page displayed (what the server gives back to the browser) if the 
     * solutions are supposed to be released.
     *
     * We also use some PHP to create unique IDs for each of the show/hide 
     * buttons and solution divs, which are then used in the PHP generated 
     * jQuery code that we use to create the nice toggling effect.
     *
     * I apologize if the PHP/jQuery is really offensively bad, this is 
     * literally the most I've written of either for a single project so far.
     * Comments/suggestions are most welcome!
     *
     * - Tom Magrino (tmagrino@berkeley.edu)
     */
    $BERKELEY_TZ = new DateTimeZone("America/Los_Angeles");
    $RELEASE_DATE = new DateTime("4/10/2014", $BERKELEY_TZ);
    $CUR_DATE = new DateTime("now", $BERKELEY_TZ);
    $q_num = 0; // Used to make unique ids for all solutions and buttons
    ?>
  </head> 
  <body style="font-family: Georgia,serif;">
    <h1 id="title-main">CS 61A Lab 8</h1>
<h2 id="title-sub">Scheme and Calculator</h2>
<h2>Starter Files</h2>

<p>We've provided a set of starter files with skeleton code for the
exercises in the lab. You can get them in the following places:</p>

<ul>
<li><a href="starter/scheme.scm">scheme.scm</a></li>
<li><a href="starter/buffer.py">buffer.py</a></li>
<li><a href="starter/ucb.py">ucb.py</a></li>
<li><a href="starter/scheme_reader.py">scheme_reader.py</a></li>
<li><a href="starter/calc.py">calc.py</a></li>
<li><a href="starter/scheme_tokens.py">scheme_tokens.py</a></li>
</ul>

<h2>Scheme</h2>

<p>Scheme is a famous functional programming language from the 1970s.  It
is a dialect of Lisp (which stands for LISt Processing).  The first
observation most people make is the unique syntax, which uses
Polish-prefix notation and (often many) nested parenthesis.  (See:
<a href="http://xkcd.com/297/">http://xkcd.com/297/</a>).
Scheme features first-class functions and optimized tail-recursion,
which were relatively new features at the time.</p>

<h3>Primitives and Functions</h3>

<p>Let's take a look at the primitives in Scheme. Open up the Scheme
interpreter in your terminal with the command <code>stk</code>, and try typing in
the following expressions to see what Scheme would print.</p>

<pre><code>STk&gt; 1          ; Anything following a ';' is a comment
?
STk&gt; 1.0
?
STk&gt; -27
?
STk&gt; #t         ; True
?
STk&gt; #f         ; False
?
STk&gt; "A string"
?
STk&gt; 'symbol
?
STk&gt; nil
()
</code></pre>

<p>Of course, what kind of programming language would Scheme be if it
didn't have any functions? Scheme uses Polish prefix notation, where
the operator comes before the operands. For example, to evaluate <code>3 +
4</code>, we would type into the Scheme interpreter:</p>

<pre><code>STk&gt; (+ 3 4)
</code></pre>

<p>Notice that to <em>call</em> a function we need to enclose it in parenthesis,
with its arguments following. Be careful about this, as while in
Python an extra set of parentheses won't hurt, in Scheme, it will
interpret the parentheses as a function call. Evaluating <code>(3)</code> results
in an error because Scheme tries to call a function called <code>3</code> that
takes no arguments, which would result in an error.</p>

<p>Let's familiarize ourselves with some of the built-in functions in
Scheme. Try out the following in the interpreter</p>

<pre><code>STk&gt; (+ 3 5)
?
STk&gt; (- 10 4)
?
STk&gt; (* 7 6)
?
STk&gt; (/ 28 2)
?
STk&gt; (+ 1 2 3 4 5 6 7 8 9)      ;Arithmetic operators allow a variable number of arguments
?
STk&gt; (abs -7)             ;Absolute Value
?
STk&gt; (quotient 29 5)
?
STk&gt; (remainder 29 5)
?
STk&gt; (= 1 3)
?
STk&gt; (&gt; 1 3)
?
STk&gt; (&lt; 1 3)
?
STk&gt; (or #t #f)
?
STk&gt; (and #t #t)
?
STk&gt; (and #t #f (/ 1 0))        ;Short-Circuiting
?
STk&gt; (not #t)
?
STk&gt; (define x 3)               ;Defining Variables
STk&gt; x
?
STk&gt; (define y (+ x 4))
STk&gt; y
?
STk&gt; nil
?
</code></pre>

<p>To write a program, we need to write functions, so let's define one.
The syntax for defining a program in Scheme is:</p>

<pre><code>(define ([name] [args])
        [body])
</code></pre>

<p>For example, let's define the <code>double</code> function:</p>

<pre><code>(define (double x)
        (+ x x))
</code></pre>

<h3 class='question'>Question 1</h3>

<p>Try it yourself! Define a function which cubes an input. You can load
your definitions into Scheme by entering <code>stk -load filename.scm</code> in
your terminal, or if you have the interpreter already opened up <code>(load
"filename.scm")</code>.</p>

<pre><code>(define (cube x)
        0)          ; Replace the 0 with your code
</code></pre>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton0">Toggle Solution</button>
  <div id="toggleText0" style="display: none">
    <pre><code>(define (cube x)
        (* x x x))
</code></pre>

  </div>
<?php } ?>
<h3>Control and Recursion</h3>

<p>So far, we can't do much in our functions so let's introduce control
statements to allow our functions to do more complex operations! The
if-statement has the following format:</p>

<pre><code>(if [condition]
    [true_result]
    [false_result])
</code></pre>

<p>For example, the following code written in Scheme and Python are
equivalent:</p>

<pre><code>;Scheme
(if (&gt; x 3)
    1
    2)

#Python
if x &gt; 3:
    1
else:
    2
</code></pre>

<p>Notice that the if-statement has no <code>elif</code> case. If want to have
multiple cases with the if-statement, you would need multiple branched
if-statements:</p>

<pre><code>;Scheme
(if (&lt; x 0)
    (display "Negative")
    (if (= x 0)
        (display "Zero")
        (display "Positive")))

#Python Equivalent
if x &lt; 0:
    print('Negative')
else:
    if x == 0:
        print('Zero')
    else:
        print('Positive')
</code></pre>

<p>But this gets messy as more cases are needed, so alternatively, we
also have the <code>cond</code> statement, which has a different syntax:</p>

<pre><code>(cond ([condition_1] [result_1])
      ([condition_2] [result_2])
        ...
      ([condition_n] [result_n])
      (else [result_else]))                ;'else' is optional
</code></pre>

<p>With this, we can write control statements with multiple cases neatly
and without needing branching if-statements.</p>

<pre><code>(cond ((and (&gt; x 0) (= (modulo x 2) 0)) (display "Positive Even Integer"))
      ((and (&gt; x 0) (= (modulo x 2) 1)) (display "Positive Odd Integer"))
      ((= x 0) (display "Zero"))
      ((and (&lt; x 0) (= (modulo x 2) 0)) (display "Negative Even Integer"))
      ((and (&lt; x 0) (= (modulo x 2) 1)) (display "Negative Odd Integer")))
</code></pre>

<p>Now that we have control statements in Scheme, let us revisit a
familiar problem: factorial.  We will write it using a recursive
procedure, but with both recursive and iterative <em>processes</em>. Read
section 1.2.1 of this
<a href="http://mitpress.mit.edu/sicp/full-text/book/book-Z-H-11.html">link</a>
to see what we mean by this.  In short, an iterative process is
summarized by state variables (for example, a counter and a sum), and
update and termination rules. The iterative <em>process</em> can still use an
underlying recursive procedure.</p>

<h3 class='question'>Question 2</h3>

<ol>
<li>Write <code>factorial</code> with a recursive process.</li>
<li>Write <code>factorial</code> with an iterative process. The procedure should
still make a recursive call!</li>
</ol>

<p>In the iterative case, most programming languages consume memory
proportional to the number of procedure calls.  However, Scheme "will
execute an iterative process in constant space, even if the iterative
process is described by a recursive procedure. An implementation with
this property is called tail-recursive."  We see then that syntax such
as "for" and "while" loops are not necessary in Scheme; they are
merely "syntactic sugar".</p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton1">Toggle Solution</button>
  <div id="toggleText1" style="display: none">
    <pre><code>; Regular recursion
(define (factorial_recursive x)
    (if (= x 0)
        1
        (* x (factorial_recursive (- x 1)))))

; tail recursively
(define (factorial_iterative x)
    (fact_iter_helper 1 1 x))

(define (fact_iter_helper prod count max)
    (if (&gt; count max)
        product
        (fact_iter_helper (* prod count) (+ count 1) max)))
</code></pre>

  </div>
<?php } ?>
<h3>Lists</h3>

<p>In Scheme, lists are composed similarly to how Rlists that we had worked with
in Python were implemented. Lists are made up pairs, which can point to two
objects. To create a pair, we use the <code>cons</code> function,
which takes two arguments:</p>

<pre><code>STk&gt; (define a (cons 3 5))
a
STk&gt; a
(3 . 5)
</code></pre>

<p>Note the dot between the <code>3</code> and <code>5</code>. The dot indicates that this is a
pair, rather than a sequence (as you'll see in a bit).</p>

<p>To retrive a value from a pair, we use the <code>car</code> and <code>cdr</code> functions
to retrieve the first and second elements in the pair.</p>

<pre><code>STk&gt; (car a)
3
STk&gt; (cdr a)
5
</code></pre>

<p>Look familiar yet? Like how Rlists are formed, lists in Scheme are
formed by having the first element of a pair be the first element of
the list, and the second element of the pair point to another pair
containing the rest of list, or to <code>nil</code> to signify the end of the
list. For example, the sequence (1, 2, 3) can be represented in Scheme
with the following line:</p>

<pre><code>STk&gt; (define a (cons 1 (cons 2 (cons 3 nil))))
</code></pre>

<p>which creates the following object in Scheme:</p>

<p><img src="assets/list1.png" alt="rlist" /></p>

<p>We can then of course retrieve values from our list with the <code>car</code> and
<code>cdr</code> function.</p>

<pre><code>STk&gt; a
(1 2 3)
STk&gt; (car a)
1
STk&gt; (cdr a)
(2 3)
STk&gt; (car (cdr (cdr a)))
3
</code></pre>

<p>This is not the only way to create a list though. You can also use the
<code>list</code> function, as well as the quote form to form a list as well:</p>

<pre><code>STk&gt; (list 1 2 3)
(1 2 3)
STk&gt; '(1 2 3)
(1 2 3)
STk&gt; '(1 . (2 . (3)))
</code></pre>

<p>There are a few other built-in functions in Scheme that are used for lists:</p>

<pre><code>STk&gt; (define a '(1 2 3 4))
a
STk&gt; (define b '(4 5 6))
b
STk&gt; (define empty '())
empty
STk&gt; (append '(1 2 3) '(4 5 6))
(1 2 3 4 5 6)
STk&gt; (length '(1 2 3 4 5))
5
STk&gt; (null? '(1 2 3))             ;Checks whether list is empty.
#f
STk&gt; (null? '())
#t
STk&gt; (null? nil)
#t
</code></pre>

<h3 class='question'>Question 3</h3>

<p>Create the structure as defined in the picture below. Check to make
sure that your solution is correct!</p>

<p><img src="assets/list2.png" alt="rlist" /></p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton2">Toggle Solution</button>
  <div id="toggleText2" style="display: none">
    <pre><code>(define structure (cons (cons 1 '()) (cons 2 (cons (cons 3 4) (cons 5 '())))))
</code></pre>

  </div>
<?php } ?>
<h3 class='question'>Question 4</h3>

<p>Implement a function <code>(remove item lst)</code> that takes in a list and
returns a new list with <code>item</code> removed from lst.</p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton3">Toggle Solution</button>
  <div id="toggleText3" style="display: none">
    <pre><code>(define (remove item lst)
    (cond ((null? lst) '())
          ((equal? item (car lst)) (remove item (cdr lst)))
          (else (cons (car lst) (remove item (cdr lst))))))
</code></pre>

  </div>
<?php } ?>
<h3 class='question'>Question 5</h3>

<p>Create a function <code>(filter f lst)</code>, which takes a predicate function
and a list, and returns a new list containing only elements of the
list that satisfy the predicate.</p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton4">Toggle Solution</button>
  <div id="toggleText4" style="display: none">
    <p>(define (filter f lst)
    (cond ((null? lst) '())
          ((f (car lst)) (cons (car lst) (filter f (cdr lst))))
          (else (filter f (cdr lst)))))</p>

  </div>
<?php } ?>
<h3 class='question'>Question 6</h3>

<p>Implement a function <code>(all_satisfies lst pred)</code> that returns #t if all
of the elements in the list satisfy pred.</p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton5">Toggle Solution</button>
  <div id="toggleText5" style="display: none">
    <pre><code>(define (all_satisfies lst pred)
        (= (length (filter pred lst)) (length lst)))
</code></pre>

  </div>
<?php } ?>
<h3>More Scheme</h3>

<p>There's a lot to Scheme, which is perhaps too much for this lab.
Scheme takes a bit of time to get used to, but it's really not much
different from any other language. The important thing is to just try
different things and learn through practice. You can find more
exercises <a href="lab8-scheme.html">here</a>.</p>

<h2>Calculator</h2>

<p>We are continuing our journey through the world of interpreters! We
have seen interpreters before -- that thing you've been running all of
your Python in? That's an interpreter! In fact, the Python interpreter
we've been using all semester long is just a program, albeit a very
complex one. You can think of it as a program that takes strings as
input, evaluates them, and prints the result as output.</p>

<p>There's nothing magical about interpreters, though! They are programs,
just like the ones you have been writing throughout the entire semester.
In fact, it's possible to write a
<a href="http://pypy.org">Python interpreter in Python</a>! However, because
Python is a complex language, writing an interpreter for it would be a
very daunting project. Today, we'll play around with an interpreter for
a calculator language.</p>

<p>In lecture, you were introduced to Calc, which acts as a simple
calculator.  You should have already copied the starter files
(<code>scheme_reader.py</code>, <code>calc.py</code>, etc.) at the start of the lab. You can
try running Calc by running this command in the terminal:</p>

<pre><code>python3 calc.py
</code></pre>

<p>To exit the program, type Ctrl-D or Ctrl-C.</p>

<h3 class='question'>Question 7</h3>

<p>Trace through the code in <code>calc.py</code> that would be evaluated when you
type the following into calc.</p>

<pre><code>&gt; 2
&gt; (+ 2 3)
&gt; (+ 2 3 4)
&gt; (+ 2 3)
&gt; (+ 2)
&gt; (+ 2 (* 4 5))
</code></pre>

<h2>Infix notation</h2>

<p>While prefix notation <code>(+ 2 3)</code> is easy for computers to interpret,
it's not very natural for humans. We'd much prefer infix notation
<code>(2 + 3)</code>. Let's implement this in our own version of calc!</p>

<p>To do this, you need to fill in the following functions, which are in
the <code>scheme_reader.py</code> file.</p>

<h3 class='question'>Question 8</h3>

<p>Implement <code>read_infix</code>. This function takes two arguments: <code>first</code>, the
first expression in the infix expression; and <code>src</code>, the <code>Buffer</code> of
tokens that contains the rest of the infix expression (and possibly
more). For example, if we wanted to construct <code>3 + 4 5</code> (note: the 5
will be ignored, so it's really just <code>3 + 4)</code>), we would call</p>

<pre><code>read_infix(3, Buffer(tokenize_lines('+ 4 5')))
</code></pre>

<p>The return value should be an expression which is mathematically the
same as the infix notation expression (e.g. <code>(+ 3 4)</code>), but written
using Scheme style Pairs. See the doctests for simple examples. Follow
these steps:</p>

<ol>
<li>First, check if there are more tokens left in the <code>Buffer</code>. <em>Hint</em>:
the <code>Buffer</code> class in the <code>buffer.py</code> file has a <code>more_on_line</code>
property method. If there aren't, we should just return <code>nil</code>. Also,
we would need to return <code>nil</code> if the <code>Buffer</code>'s current value
(there's already a method that gives you the current value!) is
equal to one of two things.  Think about what these two things would
be.</li>
<li>Next, figure out what the operator and second half of the infix
expression should be.</li>
<li>Finally, return a Scheme-style expression which represents the same
thing as the infix notation expression you parsed. Look at the
doctests for specific examples.</li>
</ol>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton6">Toggle Solution</button>
  <div id="toggleText6" style="display: none">
    <pre><code>def read_infix(first, src):
    if not src.more_on_line or src.current() is None or src.current() == ")":
        return nil
    op = src.pop()
    rest = scheme_read(src)
    return Pair(op, Pair(first, Pair(rest, nil)))
</code></pre>

  </div>
<?php } ?>
<h3 class='question'>Question 9</h3>

<p>Implement <code>next_is_op</code>. This function returns <code>True</code> if the next token
in the given <code>Buffer</code> is an operator, and <code>False</code> otherwise.</p>

<p><em>Hint</em>: don't forget to check if there are any tokens in the buffer
first. Also, don't remove any tokens from the <code>Buffer</code> (i.e. don't use
<code>pop</code>; think of another method you can use).</p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton7">Toggle Solution</button>
  <div id="toggleText7" style="display: none">
    <pre><code>def next_is_op(src):
    return src.more_on_line and src.current() in ['+', '-', '*', '/']
</code></pre>

  </div>
<?php } ?>
<h3 class='question'>Question 10</h3>

<p>Modify <code>scheme_read</code> to parse infix expressions. This requires
modifying two parts of the code:</p>

<ol>
<li>First, we need to determine if we're dealing with an expression like
<code>2 + 3</code>. To do this, check if the first item in the Buffer is a
float or a int (this part's already written).  If it is, then check
that the next token is an operator. If it is, read it like an infix
expression.  (Try to call methods you've already written!)
Otherwise, just return the value (this part's already written).</li>
<li><p>Next, we have to deal with the case of infix notation inside
parentheses. Without parentheses, <code>2 + 2 * 3</code> and <code>2 * 3 + 2</code> should
produce the exact same result, but in calc, they don't! calc doesn't
implement order of operations, because prefix notation naturally
takes care of operator precedence (why?).</p>

<p>Instead of solving the real problem, we'll implement a quick fix. If
we allow expressions to be surrounded by parentheses, we can write
expressions like <code>2 + (2 * 3)</code>, which will evaluate to the same
thing as <code>(2 * 3) + 2</code>.</p>

<p>To do this, we need to change how we parse lists. This logic should
be very similar to what you did in the previous part of
<code>scheme_read</code>. <em>Hint</em>: The code should be exactly like part 1, but
figure out why!</p></li>
</ol>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton8">Toggle Solution</button>
  <div id="toggleText8" style="display: none">
    <pre><code>def scheme_read(src):
    if src.current() is None:
        raise EOFError
    val = src.pop()
    if val == 'nil':
        return nil
    elif type(val) is int: #infix notation. Let's check!
        if next_is_op(src):
            return read_infix(val, src)
        return val
    elif val not in DELIMITERS:  # ( ) ' .
        return val
    elif val == "(":
        val = read_tail(src)

        #Note that this isn't the best idea of what to do.
        #We have to add this semi-hack because we're modifying the language
        #in a strange way to get infix notation.
        if val.second == nil and val.first != nil:
            val = val.first

        if next_is_op(src):
            return read_infix(val, src)
        return val
    else:
        raise SyntaxError("unexpected token: {0}".format(val))
</code></pre>

  </div>
<?php } ?>
<p>And that's it! We have infix notation! The following inputs to calc should work.</p>

<pre><code>&gt; (+ 2 2 * 3)
8
&gt; 2 + (- 5 2)
5
&gt; (+ 1 3 * 4 (* 7 4))
41
&gt; (2 * 3) + 6
12
&gt; 6 + (2 * 3)
12
</code></pre>

<h2>Defining Variables</h2>

<p>Now we're going to add the ability to define variables. For example:</p>

<pre><code>&gt; (define x 3)
x
&gt; (+ x 2)
5
&gt; (* x (+ 2 x))
15
</code></pre>

<p>For this part, we will be modifying the <code>calc.py</code> file. Do we need to
change <code>calc_eval</code>? <code>calc_apply</code>?  Is define a special form?</p>

<h3 class='question'>Question 11</h3>

<p>Implement <code>do_define_form</code>. This function takes in a Pair that contains
2 items: the variable name, and the expression to which it should be
assigned. <code>do_define_form</code> should modify the global environment, <code>env</code>
(a dictionary, defined near the top of <code>calc.py</code>) to contain the
name/value binding. It should also return the name of the variable
you're defining.</p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton9">Toggle Solution</button>
  <div id="toggleText9" style="display: none">
    <pre><code>def do_define_form(vals):
    env[vals.first] = calc_eval(vals.second.first)
    return vals.first
</code></pre>

  </div>
<?php } ?>
<h3 class='question'>Question 12</h3>

<p>Finally, implement the lookup procedure in <code>calc_eval</code>. You should
check if the given identifier exp is in the environment. If it is, then
simply return the value associated with it. If not, you should raise an
exception to signal that the user did something wrong.</p>

<?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <button id="toggleButton10">Toggle Solution</button>
  <div id="toggleText10" style="display: none">
    <pre><code>def calc_eval(exp):
    if type(exp) in (int, float):
        return simplify(exp)
    if type(exp) is str and is_identifier(exp):
        if exp in env:
            return env[exp]
        raise Error("Unbound variable " + exp)
    elif isinstance(exp, Pair):
        if exp.first == "define":
            return do_define_form(exp.second)
        arguments = exp.second.map(calc_eval)
        return simplify(calc_apply(exp.first, arguments))
    else:
        raise TypeError(exp + ' is not a number or call expression')
</code></pre>

  </div>
<?php } ?>
<p>And that's it! There you have basic variable declaration.</p>

  </body>
  <?php if ($CUR_DATE > $RELEASE_DATE) { ?>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script>
    <?php for ($i = 0; $i < 11; $i++) { ?>
      $("#toggleButton<?php echo $i; ?>").click(function () {
        $("#toggleText<?php echo $i; ?>").toggle();
    });
    <?php } ?>
  </script>
<?php } ?>
</html>
